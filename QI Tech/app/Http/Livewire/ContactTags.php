<?php

namespace App\Http\Livewire;

use App\Models\contact_groups;
use App\Models\contact_tags;
use App\Models\new_contacts;
use App\Models\tag_to_contacts;
use App\Models\tag_to_group;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ContactTags extends Component
{
    public $contact_id, $type_input, $msgGroup, $msg, $group_input;
    public $contact_groups = [];
    public $user_contact_groups = [];
    public $default_contact_groups = [];
    public $contact_default_tags = [];
    public $groups_input = [];
    public $user_groups_input = [];
    public $group_ids = [];
    public $isVisible = false;
    public $isGroupVisible = false;
    public $isEdit = false;
    public $isGroupEdit = false;
    public $isCustomTag = false;
    public $isCustomGroup = false;
    public $all_tags;
    public $category_input = [];
    public $color_input = [];
    public $selected_tag;
    public $selected_tag_icon, $selected_tag_icon_color, $selected_tag_text_color;
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
        ',
    ];
    protected $listeners = ['refreshComponent' => '$refresh'];
    public function mount($contact_id)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $contact = new_contacts::find($contact_id);
        $contact_groups = $headOffice->contact_groups;
        $this->user_contact_groups = $contact->contact_to_groups ?? null;
        $this->all_tags = $headOffice->contact_tags;

        foreach ($this->all_tags as $cat) {
            $this->category_input[$cat->id] = $cat->name ?? '';
            $this->color_input[$cat->id] = $cat->color ?? '#000';
            $this->type_input[$cat->id] = $cat->type;
            $this->groups_input[$cat->id] = $cat->tag_to_groups->pluck('group_id')->toArray();
        }

        $this->contact_id = $contact_id;
        $this->contact_groups = $contact_groups;

        foreach ($this->contact_groups as $value) {
            $this->group_input[$value->id] = $value->group_name;
            $this->color_input[$value->id] = $value->color ?? '#000';
            $this->type_input[$value->id] = $value->type;
        }


        if($this->contact_id){
            $new_contact =  new_contacts::find($this->contact_id);
            $contact_to_groups = $new_contact->contact_to_groups;
            $selected_groups_array = []; 
            foreach ($contact_to_groups as $value) {
                $selected_groups_array[] = $value->contact_group;
            }
            $this->default_contact_groups = $selected_groups_array;

            $tag_to_contacts = $new_contact->tag_to_contacts;
            $selected_tags_array = [];
            foreach ($tag_to_contacts as $value) {
                $group_ids = tag_to_group::where('tag_id', $value->tag_id)->get()->pluck('group_id')->toArray();
                $value->group_ids = $group_ids;
                $selected_tags_array[] = $value->contact_tag;
            }
            $this->contact_default_tags = $selected_tags_array;
        }
    }
    public function addNewTag()
    {
        $this->msg = null;
        $newCategory = new contact_tags();
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $newCategory->head_office_id = $headOffice->id;
        $newCategory->name = 'New Tag';
        $newCategory->save();
        if ($this->contact_id) {
            $this->all_tags = $headOffice->contact_tags;
        } else {
            $this->all_tags = $headOffice->contact_tags;
        }
    }

    public function getSvg($index, $color = 'white', $width = 24, $height = 24)
    {
        if (!isset($this->svgs[$index])) {
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
    public function closeVisible()
    {
        if ($this->isCustomTag) {
            $this->isCustomTag = false;
        } else {
            $this->isVisible = false;
        }
    }
    public function toggleEditMode()
    {
        $this->isEdit = !$this->isEdit;
        $this->msg = null;
        $this->isCustomTag = false;
    }
    public function custom_visible($tag_id)
    {
        $this->selected_tag = contact_tags::find($tag_id);
        $this->selected_tag_icon = $this->selected_tag->icon;
        $this->selected_tag_icon_color = $this->selected_tag->icon_color;
        $this->selected_tag_text_color = $this->selected_tag->text_color;
        $this->isCustomTag = !$this->isCustomTag;
    }
    public function custom_group_visible($group_id)
    {
        $this->selected_tag = contact_groups::find($group_id);
        $this->selected_tag_icon = $this->selected_tag->icon;
        $this->selected_tag_icon_color = $this->selected_tag->icon_color;
        $this->selected_tag_text_color = $this->selected_tag->text_color;
        $this->isCustomGroup = !$this->isCustomGroup;
    }
    public function assignTag($tag_id)
    {
        $this->msg = null;
        if ($tag_id) {
            $contact_tag = contact_tags::find($tag_id);
            $contact_default_tags_ids = array_column($this->contact_default_tags, 'id');
            if (!in_array($contact_tag->id, $contact_default_tags_ids)) {
                $tag_to_group = tag_to_group::where('tag_id', $tag_id)->get()->pluck('group_id')->toArray();
                $contact_tag->group_ids = $tag_to_group;
                $this->contact_default_tags[] = $contact_tag;
            }
        }
    }
    public function updateTagName($tag_id)
    {
        $this->msg = null;
        $inputValue = $this->category_input[$tag_id] ?? 'New Tag';
        $updatedCategory = contact_tags::find($tag_id);
        $updatedCategory->name = $inputValue;
        $updatedCategory->save();
    }
    public function updateTagColor($tag_id)
    {
        $this->msg = null;
        $inputValue = $this->color_input[$tag_id] ?? '#000';
        $updatedCategory = contact_tags::find($tag_id);
        $updatedCategory->color = $inputValue;
        $updatedCategory->save();
    }
    public function updateGroupColor($tag_id)
    {
        $this->msg = null;
        $inputValue = $this->color_input[$tag_id] ?? '#000';
        $updatedCategory = contact_groups::find($tag_id);
        $updatedCategory->color = $inputValue;
        $updatedCategory->save();
    }
    public function updateTagCustomIconColor($tag_id)
    {
        $this->msg = null;
        $updatedCategory = contact_tags::find($tag_id);
        $updatedCategory->icon_color = $this->selected_tag_icon_color;
        $updatedCategory->save();
    }
    public function updateTagCustomTextColor($tag_id)
    {
        $this->msg = null;
        $updatedCategory = contact_tags::find($tag_id);
        $updatedCategory->text_color = $this->selected_tag_text_color;
        $updatedCategory->save();
    }
    public function updateTagCustomIcon($tag_id, $icon_index)
    {
        $this->msg = null;
        $updatedCategory = contact_tags::find($tag_id);
        $updatedCategory->icon = $icon_index;
        $updatedCategory->save();
    }

    public function updateGroupCustomIconColor($tag_id)
    {
        $this->msg = null;
        $updatedCategory = contact_groups::find($tag_id);
        $updatedCategory->icon_color = $this->selected_tag_icon_color;
        $updatedCategory->save();
        $this->refreshDefaultContactGroups();
    }
    public function updateGroupCustomTextColor($tag_id)
    {
        $this->msg = null;
        $updatedCategory = contact_groups::find($tag_id);
        $updatedCategory->text_color = $this->selected_tag_text_color;
        $updatedCategory->save();
        $this->refreshDefaultContactGroups();
    }
    public function updateGroupCustomIcon($tag_id, $icon_index)
    {
        $this->msg = null;
        $updatedCategory = contact_groups::find($tag_id);
        $updatedCategory->icon = $icon_index;
        $updatedCategory->save();
        $this->refreshDefaultContactGroups();
    }

    public function refreshDefaultContactGroups()
{
    $new_contact = new_contacts::find($this->contact_id);

    if ($new_contact) {
        $contact_to_groups = $new_contact->contact_to_groups;

        $this->default_contact_groups = [];
        foreach ($contact_to_groups as $value) {
            $this->default_contact_groups[] = $value->contact_group;
        }
    }
}


    public function removeCategory($tag_id)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $this->msg = null;
        $delTag = contact_tags::find($tag_id);

        $delTag->delete();
        $this->all_tags = contact_tags::where('head_office_id', $headOffice->id)->get();
        foreach ($this->contact_default_tags as $key => $value) {
            if ($value['id'] == $tag_id) {
                unset($this->contact_default_tags[$key]);
            }
        }
    }
    public function removeTag($tag_id)
    {
        foreach ($this->contact_default_tags as $key => $value) {
            if ($value['id'] == $tag_id) {
                unset($this->contact_default_tags[$key]);
            }
        }
    }

    public function updateTagType($tag_id)
    {
        $this->msg = null;
        $inputValue = $this->type_input[$tag_id] ?? 'general';
        $updatedCategory = contact_tags::find($tag_id);
        $updatedCategory->type = $inputValue;
        $updatedCategory->save();
        if ($updatedCategory->type == 'general') {
            $updatedCategory->tag_to_groups()->delete();
        }
    }

    public function updateTagGroups($tag_id)
    {
        if ($tag_id) {
            $this->msg = null;
            $contact_tag = contact_tags::find($tag_id);
            $contact_tag->tag_to_groups()->delete();
            foreach ($this->groups_input[$tag_id] as $key => $value) {
                $tag_to_group = new tag_to_group();
                $tag_to_group->tag_id = $tag_id;
                $tag_to_group->group_id = $value;
                $tag_to_group->save();
            }
        }
    }

    public function addNewGroup()
    {
        $this->msgGroup = null;
        $newCategory = new contact_groups();
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $newCategory->head_office_id = $headOffice->id;
        $newCategory->group_name = 'New Group';
        $newCategory->save();
        if ($this->contact_id) {
            $this->contact_groups = $headOffice->contact_groups;
        } else {
            $this->contact_groups = $headOffice->contact_groups;
        }
        foreach ($this->contact_groups as $group) {
            $this->group_input[$group->id] = $group->group_name ?? '';
        }
    }

    public function toggleGroupVisibility()
    {
        $this->isGroupVisible = !$this->isGroupVisible;
        $this->msgGroup = null;
    }
    public function closeGroupVisible()
    {
        $this->isGroupVisible = false;
    }
    public function toggleGroupEditMode()
    {
        $this->isGroupEdit = !$this->isGroupEdit;
        $this->msgGroup = null;
    }

    public function assignGroup($group_id)
    {
        $this->msgGroup = null;
        if ($group_id) {
            $contact_group = contact_groups::find($group_id);
            $contact_default_group_ids = array_column($this->default_contact_groups, 'id');
            if (!in_array($contact_group->id, $contact_default_group_ids)) {
                $this->default_contact_groups[] = $contact_group;
            }
        }
    }
    public function updateGroupName($group_id)
    {
        $this->msgGroup = null;
        $inputValue = $this->group_input[$group_id] ?? 'New Group';
        $updatedGroup = contact_groups::find($group_id);
        $updatedGroup->group_name = $inputValue;
        $updatedGroup->save();
    }
    public function deleteGroup($group_id)
    {
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $this->msgGroup = null;
        $delGroup = contact_groups::find($group_id);

        $delGroup->delete();
        $this->contact_groups = contact_groups::where('head_office_id', $headOffice->id)->get();
        foreach ($this->default_contact_groups as $key => $value) {
            if ($value['id'] == $group_id) {
                unset($this->default_contact_groups[$key]);
            }
        }
    }
    public function removeGroup($group_id)
    {
        foreach ($this->default_contact_groups as $key => $value) {
            if ($value['id'] == $group_id) {
                unset($this->default_contact_groups[$key]);
                foreach ($this->contact_default_tags as $tagKey => $tag) {
                    if ($tag['type'] == 'group_specific' && in_array($group_id, $tag['group_ids'])) {
                        unset($this->contact_default_tags[$tagKey]);
                    }
                }
            }
        }
    }
    public function render()
    {
        return view('livewire.contact-tags');
    }
}
