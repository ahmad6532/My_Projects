<?php

namespace App\Http\Livewire;

use App\Models\Address;
use App\Models\address_tags;
use App\Models\new_contact_addresses;
use App\Models\tab_to_addresses;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddressTagsManager extends Component
{
    public $case_default_tags,$address_id;
    public $isVisible = false;
    public $isEdit = false;
    public $isCustomTag = false;
    public $all_tags;
    public $category_input = [];
    public $color_input = [];
    public $selected_tag;
    public $selected_tag_icon,$selected_tag_icon_color,$selected_tag_text_color;
    public $svgs = [
        '
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 8V12M12 16H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 8.00008V12.0001M12 16.0001H12.01M3 7.94153V16.0586C3 16.4013 3 16.5726 3.05048 16.7254C3.09515 16.8606 3.16816 16.9847 3.26463 17.0893C3.37369 17.2077 3.52345 17.2909 3.82297 17.4573L11.223 21.5684C11.5066 21.726 11.6484 21.8047 11.7985 21.8356C11.9315 21.863 12.0685 21.863 12.2015 21.8356C12.3516 21.8047 12.4934 21.726 12.777 21.5684L20.177 17.4573C20.4766 17.2909 20.6263 17.2077 20.7354 17.0893C20.8318 16.9847 20.9049 16.8606 20.9495 16.7254C21 16.5726 21 16.4013 21 16.0586V7.94153C21 7.59889 21 7.42756 20.9495 7.27477C20.9049 7.13959 20.8318 7.01551 20.7354 6.91082C20.6263 6.79248 20.4766 6.70928 20.177 6.54288L12.777 2.43177C12.4934 2.27421 12.3516 2.19543 12.2015 2.16454C12.0685 2.13721 11.9315 2.13721 11.7985 2.16454C11.6484 2.19543 11.5066 2.27421 11.223 2.43177L3.82297 6.54288C3.52345 6.70928 3.37369 6.79248 3.26463 6.91082C3.16816 7.01551 3.09515 7.13959 3.05048 7.27477C3 7.42756 3 7.59889 3 7.94153Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 8V12M12 16H12.01M2 8.52274V15.4773C2 15.7218 2 15.8441 2.02763 15.9592C2.05213 16.0613 2.09253 16.1588 2.14736 16.2483C2.2092 16.3492 2.29568 16.4357 2.46863 16.6086L7.39137 21.5314C7.56432 21.7043 7.6508 21.7908 7.75172 21.8526C7.84119 21.9075 7.93873 21.9479 8.04077 21.9724C8.15586 22 8.27815 22 8.52274 22H15.4773C15.7218 22 15.8441 22 15.9592 21.9724C16.0613 21.9479 16.1588 21.9075 16.2483 21.8526C16.3492 21.7908 16.4357 21.7043 16.6086 21.5314L21.5314 16.6086C21.7043 16.4357 21.7908 16.3492 21.8526 16.2483C21.9075 16.1588 21.9479 16.0613 21.9724 15.9592C22 15.8441 22 15.7218 22 15.4773V8.52274C22 8.27815 22 8.15586 21.9724 8.04077C21.9479 7.93873 21.9075 7.84119 21.8526 7.75172C21.7908 7.6508 21.7043 7.56432 21.5314 7.39137L16.6086 2.46863C16.4357 2.29568 16.3492 2.2092 16.2483 2.14736C16.1588 2.09253 16.0613 2.05213 15.9592 2.02763C15.8441 2 15.7218 2 15.4773 2H8.52274C8.27815 2 8.15586 2 8.04077 2.02763C7.93873 2.05213 7.84119 2.09253 7.75172 2.14736C7.6508 2.2092 7.56432 2.29568 7.39137 2.46863L2.46863 7.39137C2.29568 7.56432 2.2092 7.6508 2.14736 7.75172C2.09253 7.84119 2.05213 7.93873 2.02763 8.04077C2 8.15586 2 8.27815 2 8.52274Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 8V12M12 16H12.01M7.8 21H16.2C17.8802 21 18.7202 21 19.362 20.673C19.9265 20.3854 20.3854 19.9265 20.673 19.362C21 18.7202 21 17.8802 21 16.2V7.8C21 6.11984 21 5.27976 20.673 4.63803C20.3854 4.07354 19.9265 3.6146 19.362 3.32698C18.7202 3 17.8802 3 16.2 3H7.8C6.11984 3 5.27976 3 4.63803 3.32698C4.07354 3.6146 3.6146 4.07354 3.32698 4.63803C3 5.27976 3 6.11984 3 7.8V16.2C3 17.8802 3 18.7202 3.32698 19.362C3.6146 19.9265 4.07354 20.3854 4.63803 20.673C5.27976 21 6.11984 21 7.8 21Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M11.9998 8.99999V13M11.9998 17H12.0098M10.6151 3.89171L2.39019 18.0983C1.93398 18.8863 1.70588 19.2803 1.73959 19.6037C1.769 19.8857 1.91677 20.142 2.14613 20.3088C2.40908 20.5 2.86435 20.5 3.77487 20.5H20.2246C21.1352 20.5 21.5904 20.5 21.8534 20.3088C22.0827 20.142 22.2305 19.8857 22.2599 19.6037C22.2936 19.2803 22.0655 18.8863 21.6093 18.0983L13.3844 3.89171C12.9299 3.10654 12.7026 2.71396 12.4061 2.58211C12.1474 2.4671 11.8521 2.4671 11.5935 2.58211C11.2969 2.71396 11.0696 3.10655 10.6151 3.89171Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M22 7.99992V11.9999M10.25 5.49991H6.8C5.11984 5.49991 4.27976 5.49991 3.63803 5.82689C3.07354 6.11451 2.6146 6.57345 2.32698 7.13794C2 7.77968 2 8.61976 2 10.2999L2 11.4999C2 12.4318 2 12.8977 2.15224 13.2653C2.35523 13.7553 2.74458 14.1447 3.23463 14.3477C3.60218 14.4999 4.06812 14.4999 5 14.4999V18.7499C5 18.9821 5 19.0982 5.00963 19.1959C5.10316 20.1455 5.85441 20.8968 6.80397 20.9903C6.90175 20.9999 7.01783 20.9999 7.25 20.9999C7.48217 20.9999 7.59826 20.9999 7.69604 20.9903C8.64559 20.8968 9.39685 20.1455 9.49037 19.1959C9.5 19.0982 9.5 18.9821 9.5 18.7499V14.4999H10.25C12.0164 14.4999 14.1772 15.4468 15.8443 16.3556C16.8168 16.8857 17.3031 17.1508 17.6216 17.1118C17.9169 17.0756 18.1402 16.943 18.3133 16.701C18.5 16.4401 18.5 15.9179 18.5 14.8736V5.1262C18.5 4.08191 18.5 3.55976 18.3133 3.2988C18.1402 3.05681 17.9169 2.92421 17.6216 2.88804C17.3031 2.84903 16.8168 3.11411 15.8443 3.64427C14.1772 4.55302 12.0164 5.49991 10.25 5.49991Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M15.0002 19C15.0002 20.6569 13.6571 22 12.0002 22C10.3434 22 9.00025 20.6569 9.00025 19M13.7968 6.23856C14.2322 5.78864 14.5002 5.17562 14.5002 4.5C14.5002 3.11929 13.381 2 12.0002 2C10.6195 2 9.50025 3.11929 9.50025 4.5C9.50025 5.17562 9.76825 5.78864 10.2037 6.23856M2.54707 8.32296C2.53272 6.87161 3.3152 5.51631 4.57928 4.80306M21.4534 8.32296C21.4678 6.87161 20.6853 5.51631 19.4212 4.80306M18.0002 11.2C18.0002 9.82087 17.3681 8.49823 16.2429 7.52304C15.1177 6.54786 13.5915 6 12.0002 6C10.4089 6 8.88283 6.54786 7.75761 7.52304C6.63239 8.49823 6.00025 9.82087 6.00025 11.2C6.00025 13.4818 5.43438 15.1506 4.72831 16.3447C3.92359 17.7056 3.52122 18.3861 3.53711 18.5486C3.55529 18.7346 3.58876 18.7933 3.73959 18.9036C3.87142 19 4.53376 19 5.85844 19H18.1421C19.4667 19 20.1291 19 20.2609 18.9036C20.4117 18.7933 20.4452 18.7346 20.4634 18.5486C20.4793 18.3861 20.0769 17.7056 19.2722 16.3447C18.5661 15.1506 18.0002 13.4818 18.0002 11.2Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        ',
        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M11 4H7.8C6.11984 4 5.27976 4 4.63803 4.32698C4.07354 4.6146 3.6146 5.07354 3.32698 5.63803C3 6.27976 3 7.11984 3 8.8V16.2C3 17.8802 3 18.7202 3.32698 19.362C3.6146 19.9265 4.07354 20.3854 4.63803 20.673C5.27976 21 6.11984 21 7.8 21H15.2C16.8802 21 17.7202 21 18.362 20.673C18.9265 20.3854 19.3854 19.9265 19.673 19.362C20 18.7202 20 17.8802 20 16.2V13M13 17H7M15 13H7M20.1213 3.87868C21.2929 5.05025 21.2929 6.94975 20.1213 8.12132C18.9497 9.29289 17.0503 9.29289 15.8787 8.12132C14.7071 6.94975 14.7071 5.05025 15.8787 3.87868C17.0503 2.70711 18.9497 2.70711 20.1213 3.87868Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        '
    ];
    public function mount($address_id){
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $case = new_contact_addresses::find($address_id);
        if($address_id){
            $this->case_default_tags = $case->tag_to_addresses;
            if(count($this->case_default_tags) == 0){
                $firstTag = address_tags::where('head_office_id',$headOffice->id)->first();
                if(!isset($firstTag)){
                    $firstTag = new address_tags();
                    $firstTag->head_office_id = $headOffice->id;
                    $firstTag->address_id = $case->id;
                    $firstTag->name = 'High Risk';
                    $firstTag->save();
                }
            }
        }
        $this->all_tags = address_tags::where('head_office_id',$headOffice->id)->get();
        foreach ($this->all_tags as $cat) {
            
            $this->category_input[$cat->id] = $cat->name ?? '';
            $this->color_input[$cat->id] = $cat->color ?? '#000';
        }
        $this->address_id = $address_id;
    }
    public function addNewTag(){
        $this->msg = null;
        $newCategory = new address_tags();
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $newCategory->head_office_id = $headOffice->id;
        $newCategory->address_id = $this->address_id;
        $newCategory->name = 'New Tag';
        $newCategory->save();
        if($this->address_id){
            $this->all_tags = address_tags::where('head_office_id',$headOffice->id)->get();
        }else{
            $this->all_tags = address_tags::where('head_office_id',$headOffice->id)->get();
        }
        // foreach ($this->all_tags as $cat) {
            
        //     $this->category_input[$cat->id] = $cat->name ?? '';
        // }
    }

    public function getSvg($index,$color='white',$width=24,$height=24){
        if(!isset($this->svgs[$index])){
            return null;
        }

        $svg = $this->svgs[$index];
        $svg = preg_replace('/(?<!stroke-)width="\d+"/', 'width="' . $width . '"', $svg);
        $svg = preg_replace('/height="\d+"/', 'height="' . $height . '"', $svg);        
        $svg = preg_replace('/stroke="[^"]+"/', 'stroke="' . $color . '"', $svg);
        
        return $svg;
    }

    public function toggleTagVisibility()
    {
        $this->isVisible = !$this->isVisible;
        $this->msg = null;
        $this->isCustomTag = false;
        
    }
    public function closeVisible(){
        if($this->isCustomTag){
            $this->isCustomTag = false;
            // $this->isVisible = false;
        }else{
            $this->isVisible = false;
        }
    }
    public function toggleEditMode(){
        $this->isEdit = !$this->isEdit;
        $this->msg = null;
        $this->isCustomTag = false;
    }
    public function custom_visible($tag_id){
        $this->selected_tag = address_tags::find($tag_id);
        $this->selected_tag_icon = $this->selected_tag->icon;
        $this->selected_tag_icon_color = $this->selected_tag->icon_color;
        $this->selected_tag_text_color = $this->selected_tag->text_color;
        $this->isCustomTag = !$this->isCustomTag;
    }
    public function assignTag($tag_id){
        // $this->category_id = $category_id;
        $this->msg = null;
        if($tag_id){
            $headOffice = Auth::guard('web')->user()->selected_head_office;
            if($this->address_id){
                $case = new_contact_addresses::where('id',$this->address_id)->first();
                $address_tag = $case->tag_to_addresses->where('tag_id',$tag_id)->first();
                if(!isset($address_tag)){
                    $new_tag_relation = new tab_to_addresses();
                    $new_tag_relation->tag_id = $tag_id;
                    $new_tag_relation->address_id = $this->address_id;
                    $new_tag_relation->save();
                }else{
                    if(isset($address_tag)){
                        $address_tag->delete();
                    }
                }
            }else{
                // $this->emit('categoryUpdated',$this->category_id);
            }
            $case = new_contact_addresses::find($this->address_id);
            $this->case_default_tags = $case->tag_to_addresses;
        }
    }
    public function updateTagName($tag_id){
        $this->msg = null;
        $inputValue = $this->category_input[$tag_id] ?? 'New Tag';
        $updatedCategory = address_tags::find($tag_id);
        $updatedCategory->name = $inputValue;
        $updatedCategory->save();
    }
    public function updateTagColor($tag_id){
        $this->msg = null;
        $inputValue = $this->color_input[$tag_id] ?? '#000';
        $updatedCategory = address_tags::find($tag_id);
        $updatedCategory->color = $inputValue;
        $updatedCategory->save();
    }
    public function updateTagCustomIconColor($tag_id){
        $this->msg = null;
        $updatedCategory = address_tags::find($tag_id);
        $updatedCategory->icon_color = $this->selected_tag_icon_color;
        $updatedCategory->save();
    }
    public function updateTagCustomTextColor($tag_id){
        $this->msg = null;
        $updatedCategory = address_tags::find($tag_id);
        $updatedCategory->text_color = $this->selected_tag_text_color;
        $updatedCategory->save();
    }
    public function updateTagCustomIcon($tag_id,$icon_index){
        $this->msg = null;
        $updatedCategory = address_tags::find($tag_id);
        $updatedCategory->icon = $icon_index;
        $updatedCategory->save();
    }

    public function removeCategory($tag_id){

        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $this->msg = null;
        $delTags = tab_to_addresses::where('tag_id',$tag_id)->get();
        if(count($delTags) !== 0){
            $this->msg = 'Tag in use. Choose another for deletion.';
            
        }
        else{
            $delTag = address_tags::find($tag_id);
            if(isset($delTag)){
                $delTag->delete();
                $headOffice = $headOffice->fresh();
            }else{
                $this->msg = 'Tag not found.';
            }
        }
        $this->all_tags = address_tags::where('head_office_id',$headOffice->id)->get();
    }
    public function removeTag($tag_id)
    {
        $address = new_contact_addresses::find($this->address_id);
    
        $address_tags = $address->tag_to_addresses->where('tag_id',$tag_id)->first();
        if(isset($address_tags)){
            $address_tags->delete();    
        }
        $this->case_default_tags = $address->fresh()->tag_to_addresses;
    }
    
    
    public function render()
    {
        return view('livewire.address-tags-manager');
    }
}
