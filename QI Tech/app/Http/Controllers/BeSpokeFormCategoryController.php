<?php

namespace App\Http\Controllers;

use App\Models\BeSpokeFormCategory;
use App\Http\Requests\StoreBeSpokeFormCategoryRequest;
use App\Http\Requests\UpdateBeSpokeFormCategoryRequest;
use App\Models\HeadOfficeBeSpokeFormCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BeSpokeFormCategoryController extends Controller
{
   public function index()
    {
        if(Auth::guard('location')->user())
        {
            if(!Auth::guard('location')->user()->userCanUpdateSettings())
            {
                return redirect()->route('be_spoke_forms.be_spoke_form.index')->with('error','You do not have access');
            }
            $user = Auth::guard('location')->user();
            $categories = $user->beSpokeFormCategories;
            return view('location.be_spoke_forms.categories.index',compact('categories'));
        }
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $ho = $user->selected_head_office;
        $categories = $ho->headOfficeCategories;
        return view('head_office.be_spoke_forms.categories.index',compact('categories'));
    }


    public function create(StoreBeSpokeFormCategoryRequest $request,$id = null) 
    {
        $category = null;
        

        if(Auth::guard('location')->user())
        {
            if(!Auth::guard('location')->user()->userCanUpdateSettings())
            return redirect()->route('be_spoke_forms.be_spoke_form.index')->with('error','You do not have access');
            if($id)
            {
                $be_spoke_form_categories = Auth::guard('location')->user()->beSpokeFormCategories()->find($id);
                if($be_spoke_form_categories)
                {
                    $category = $be_spoke_form_categories;
                }
            }
            return view('location.be_spoke_forms.categories.create',compact('category'));
        }

        
        if($id)
        {
            $category = BeSpokeFormCategory::findOrFail($id);
        }
        return view('head_office.be_spoke_forms.categories.create',compact('category'));
    }

    public function store(StoreBeSpokeFormCategoryRequest $request,$id=null)
    {
        $category = new BeSpokeFormCategory();

        if($id)
            $category = BeSpokeFormCategory::findOrFail($id);

        if(Auth::guard('location')->user())
        {
            if(!Auth::guard('location')->user()->userCanUpdateSettings())
                return redirect()->route('be_spoke_forms.be_spoke_form.index')->with('error','You do not have access');

            $category->reference_type = 'location';
            $category->reference_id = Auth::guard('location')->user()->id;
            $category->name = $request->name;
            $category->save();
        }
        else
        {
            $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
            $user = $user->selected_head_office;
            $category->reference_type = 'head_office';
            $category->reference_id = $user->id;
            $category->name = $request->name;
            $category->save();
        }
        return back()->with('success_message','Category Created Successfully');
    }


    public function show(BeSpokeFormCategory $beSpokeFormCategory)
    {
        //
    }

    public function edit(BeSpokeFormCategory $beSpokeFormCategory)
    {
        //
    }

    public function update(UpdateBeSpokeFormCategoryRequest $request, BeSpokeFormCategory $beSpokeFormCategory)
    {
        //
    }

    public function destroy($id)
    {
        if(Auth::guard('location')->user())
        {
            if(!Auth::guard('location')->user()->userCanUpdateSettings())
            return redirect()->route('be_spoke_forms.be_spoke_form.index')->with('error','You do not have access');

            $user = Auth::guard('location')->user();
            $be_spoke_form_categories = $user->beSpokeFormCategories()->find($id);

            if($be_spoke_form_categories)
            {
                $be_spoke_form_categories->delete();
                return back()->with('success_message','Category Deleted Successfully');
            }
        }
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        $user = $user->selected_head_office;
        $category = $user->headOfficeCategories()->find($id);
        if($category)
        {
            $category->delete();
            return back()->with('success_message','Category Deleted Successfully');
        }
        return back()->with('error','Category not found');
    }
}
