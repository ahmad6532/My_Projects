<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComCountries;
use App\Models\TaxSlab;
use App\Models\TaxYear;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Validator;

class TaxController extends Controller
{

    // get tax list

    public function fetchTaxList(Request $request)
    {

        $per_page = $request->per_page ?? 10;
        $query = TaxYear::query();
        if ($request->search_by) {
            $query->whereHas('yearToCountry', function ($query) use ($request) {
                $query->where('country_name', 'like', '%' . $request->search_by . '%');
            });
        }
        $records = $query->orderBy('from_year', 'desc')->get();
        $data = [];
        $sr = 1;
        if ($records) {
            foreach ($records as $record) {
                $single = [
                    'sr' => $sr,
                    'id' => $record->id,
                    'country' => $record->yearToCountry->country_name,
                    'tax_year' => Carbon::parse($record->from_year)->format('Y') . '-' . Carbon::parse($record->to_year)->format('Y'),
                    'last_update' => Carbon::parse($record->updated_at)->format('d/m/Y'),
                ];
                $sr++;
                array_push($data, $single);
            }
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $collection = collect($data);
            $currentPageResults = $collection->slice(($currentPage - 1) * $per_page, $per_page)->values()->all();
            $paginatedResults = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

            $paginatedResults->setPath($request->url());

            return response()->json([
                'status' => 1,
                'success' => true,
                'message' => 'Tax data fetched successfully',
                'data' => array_values($paginatedResults->items()),
                'current_page' => $paginatedResults->currentPage(),
                'next_page_url' => $paginatedResults->nextPageUrl(),
                'path' => $paginatedResults->path(),
                'per_page' => $paginatedResults->perPage(),
                'prev_page_url' => $paginatedResults->previousPageUrl(),
                'to' => $paginatedResults->count() > 0 ? $paginatedResults->lastItem() : null,
                'total' => $paginatedResults->total(),
                'total_pages' => $paginatedResults->lastPage(),
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => 'Data not found',
            ]);
        }

    }

    // save tex setting
    public function saveTexSetting(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                "country_id" => "required|integer",
                "tax_from_year" => "required|date_format:Y-m",
                "tax_to_year" => "required|date_format:Y-m",
                "tax_slabs.*.sal_start_range" => "required",
                "tax_slabs.*.sal_end_range" => "required",
                "tax_slabs.*.fixed_amount" => "required",
                "tax_slabs.*.amount_exceed" => "required",
                "tax_slabs.*.tax_percent" => "required",
            ]);
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'success' => false, 'message' => $validation->messages()->first()], 200);
            }
            if ($request->tax_slabs == null) {
                return response()->json(['status' => 0, 'succees' => false, 'message' => 'Minimum 1 tax slab is required'], 200);
            } else {
                $f_year = Carbon::parse($request->tax_from_year)->year;
                $t_year = Carbon::parse($request->tax_to_year)->year;
                $taxData = TaxYear::whereYear('from_year', $f_year)
                    ->whereYear('to_year', $t_year)
                    ->where('country_id', $request->country_id)
                    ->first();
                if ($taxData) {
                    return response()->json(['status' => 0, 'succees' => false, 'message' => 'Country already exist with this year'], 200);
                }

                DB::beginTransaction();

                $from_year = (new DateTime($request->tax_from_year . '-01'))->format('Y-m-d');
                $to_year = (new DateTime($request->tax_to_year))->format('Y-m-d');
                $tax_year = TaxYear::create([
                    'country_id' => $request->country_id,
                    'from_year' => $from_year,
                    'to_year' => $to_year
                ]);
                if ($tax_year) {
                    foreach ($request->tax_slabs as $slab) {
                        TaxSlab::create([
                            'year_id' => $tax_year->id,
                            'start_range' => $slab['sal_start_range'],
                            'end_range' => $slab['sal_end_range'],
                            'fixed_amount' => $slab['fixed_amount'],
                            'amount_exceed' => $slab['amount_exceed'],
                            'tax_percent' => $slab['tax_percent'],
                            'is_deleted' => '0',
                        ]);
                    }
                }
                $countryData = ComCountries::where('country_id', $request->country_id)->where('is_deleted', 'N')->first();
                $msg = '"' . $countryData->country_name . '" tax slabs of year ' . $request->tax_from_year . ' / ' . $request->tax_to_year . ' added successfully';
                createLog('device_action', $msg);
                DB::commit();
                return response()->json(['status' => 1, 'success' => true, 'message' => 'Tax Slabs added Successfully...'], 200);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // edit tax setting
    public function editTaxSetting(Request $request)
    {
        $taxData = TaxYear::find($request->year_id);
        if ($taxData) {
            $data = [
                'id' => $taxData->id,
                'country' => $taxData->yearToCountry->country_name,
                'tax_from_year' => Carbon::parse($taxData->from_year)->format('Y-m'),
                'tax_to_year' => Carbon::parse($taxData->to_year)->format('Y-m'),
                'tax_slabs' => []
            ];
            foreach ($taxData->yearToSlabs as $record) {
                $data['tax_slabs'][] = [
                    'id' => $record->id,
                    'sal_start_range' => $record->start_range,
                    'sal_end_range' => $record->end_range,
                    'fixed_amount' => $record->fixed_amount,
                    'amount_exceed' => $record->amount_exceed,
                    'tax_percent' => $record->tax_percent,
                ];
            }
            return response()->json([
                'status' => 1,
                'success' => true,
                'message' => 'Data fetched successfully...',
                'data' => $data
            ], 200);
        } else {
            return response()->json(['error' => 'Tax Data Not Found'], 404);
        }
    }

    // update tax setting
    public function updateTaxSetting(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                "year_id" => "required",
                "tax_from_year" => "required|date_format:Y-m",
                "tax_to_year" => "required|date_format:Y-m",
                "tax_slabs.*.sal_start_range" => "required",
                "tax_slabs.*.sal_end_range" => "required",
                "tax_slabs.*.fixed_amount" => "required",
                "tax_slabs.*.amount_exceed" => "required",
                "tax_slabs.*.tax_percent" => "required",
            ]);
            if ($validation->fails()) {
                return response()->json(['status' => 0, 'success' => false, 'message' => $validation->messages()->first()], 200);
            }
            if ($request->tax_slabs == null) {
                return response()->json(['status' => 0, 'succees' => false, 'message' => 'Minimum 1 tax slab is required'], 200);
            } else {
                DB::beginTransaction();

                $from_year = (new DateTime($request->tax_from_year . '-01'))->format('Y-m-d');
                $to_year = (new DateTime($request->tax_to_year))->format('Y-m-d');
                $tax_year = TaxYear::updateOrCreate(
                    [
                        'id' => $request->year_id
                    ],
                    [
                        'from_year' => $from_year,
                        'to_year' => $to_year
                    ]
                );
                if ($tax_year) {
                    foreach ($request->tax_slabs as $slab) {
                        TaxSlab::where('year_id', $tax_year->id)->delete();
                    }
                    foreach ($request->tax_slabs as $slab) {
                        TaxSlab::create([
                            'year_id' => $tax_year->id,
                            'start_range' => $slab['sal_start_range'],
                            'end_range' => $slab['sal_end_range'],
                            'fixed_amount' => $slab['fixed_amount'],
                            'amount_exceed' => $slab['amount_exceed'],
                            'tax_percent' => $slab['tax_percent'],
                            'is_deleted' => '0',
                        ]);
                    }
                }
                DB::commit();
                return response()->json(['status' => 1, 'success' => true, 'message' => 'Tax Slabs updated Successfully...'], 200);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




}
