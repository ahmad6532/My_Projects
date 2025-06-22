<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceMessagesFormRequest;
use App\Models\ServiceMessage;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ServiceMessagesController extends Controller
{
    private $countries = [
        "England",
        "Scotland",
        "Wales",
        "Channel Islands",
        "Northern Ireland",
        "Republic of Ireland"
    ];
    private $receivers = [
        "Head Office",
        "Location",
        "User",
    ];

    /**
     * Display a listing of the service messages.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $serviceMessages = ServiceMessage::paginate(25);

        return view('admin.service_messages.index', compact('serviceMessages'));
    }

    /**
     * Show the form for creating a new service message.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $countries=$this->countries;
        $receivers=$this->receivers;
        return view('admin.service_messages.create',compact('countries','receivers'));
    }

    /**
     * Store a new service message in the storage.
     *
     * @param App\Http\Requests\ServiceMessagesFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(ServiceMessagesFormRequest $request)
    {
        try {

            $this->validate($request,[
                'duration'=>'required|min:1|numeric',
            ]);

            $data = $request->getData();

            $serviceMessage=new ServiceMessage;
            $serviceMessage->title=$data['title'];
            $serviceMessage->message=$data['message'];
            $serviceMessage->send_to=$data['send_to'];
            $serviceMessage->countries=$data['countries'];
            $serviceMessage->duration=$request->duration;
            $serviceMessage->expires_at=Carbon::now(env('TIMEZONE'))->addDays($request->duration);

            $serviceMessage->save();

            return redirect()->route('service_messages.service_message.index')
                ->with('success_message', 'Service Message was successfully added.');
        } catch (Exception $exception) {
            dd($exception->getMessage());
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified service message.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $serviceMessage = ServiceMessage::findOrFail($id);

        return view('admin.service_messages.show', compact('serviceMessage'));
    }

    /**
     * Show the form for editing the specified service message.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $serviceMessage = ServiceMessage::findOrFail($id);

        $countries=$this->countries;
        $receivers=$this->receivers;
        //dd($receivers);

        return view('admin.service_messages.edit', compact('serviceMessage','countries','receivers'));
    }

    /**
     * Update the specified service message in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\ServiceMessagesFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, ServiceMessagesFormRequest $request)
    {
        try {
            
            $data = $request->getData();
            
            $serviceMessage = ServiceMessage::findOrFail($id);
            $serviceMessage->update($data);

            return redirect()->route('service_messages.service_message.index')
                ->with('success_message', 'Service Message was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }        
    }

    /**
     * Remove the specified service message from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $serviceMessage = ServiceMessage::findOrFail($id);
            $serviceMessage->delete();

            return redirect()->route('service_messages.service_message.index')
                ->with('success_message', 'Service Message was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }


    /**
     * Show the form for extending duration the specified service message.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function extend_duration_view($id)
    {
        $serviceMessage = ServiceMessage::findOrFail($id);
        return view('admin.service_messages.extend_duration', compact('serviceMessage'));
    }

    /**
     * @param int $id
     *
     * @return Illuminate\store
     */
    public function extend_duration($id,Request $request)
    {

        try {
            $this->validate($request,[
                'duration'=>'required|min:1|numeric',
            ]);
            $duration = $request->duration;
            $serviceMessage = ServiceMessage::findOrFail($id);

            $serviceMessage->expires_at=Carbon::parse($serviceMessage->expires_at)->addDays($duration);
            $serviceMessage->duration=$serviceMessage->duration+$duration;

            $serviceMessage->save();

            return redirect()->route('service_messages.service_message.index')
                ->with('success_message', 'Duration updated.');
        } catch (Exception $exception) {
            dd($exception->getMessage());
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

}
