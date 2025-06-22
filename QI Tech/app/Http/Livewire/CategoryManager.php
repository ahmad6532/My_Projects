<?php

namespace App\Http\Livewire;

use App\Models\BeSpokeFormCategory;
use App\Models\Forms\Form;
use App\Models\near_miss_manager;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CategoryManager extends Component
{
    public $isVisible = false;
    public $isEdit = false;
    public $category;
    public $categories;
    public $category_id;
    public $form_id;
    public $category_input = [];
    public $color_input = [];
    public $near_miss_table = null;
    public $msg;
    protected $listeners = ['closeVisible' => 'closeVisible'];

    public function mount($form_id,$near_miss_table=null){
        $user = Auth::guard('web')->user()->selected_head_office;
        if($form_id){
            if($near_miss_table == true){
                $this->near_miss_table = $near_miss_table;
                $near_miss_category = near_miss_manager::find($form_id);
                if(!isset($near_miss_category->be_spoke_form_category_id)){
                    // $near_miss_category->be_spoke_form_category_id = BeSpokeFormCategory::first()->id;
                }
                $this->category = BeSpokeFormCategory::find($near_miss_category->be_spoke_form_category_id);
            }else{
                $this->category = BeSpokeFormCategory::find(Form::find($form_id)->be_spoke_form_category_id);
            }
        }else{
            $this->category = null;
            // if(!isset($this->category)){
            //     $default_category = new BeSpokeFormCategory();
            //     $default_category->reference_id = $user->id;
            //     $default_category->reference_type = 'head_office';
            //     $default_category->name = 'New Category';
            //     $default_category->save();
            // }
        }
        $this->categories = BeSpokeFormCategory::where('reference_id', $user->id)->get();
        foreach ($this->categories as $cat) {
            
            $this->category_input[$cat->id] = $cat->name ?? '';
            $this->color_input[$cat->id] = $cat->color ?? '#000';
        }
        $this->form_id = $form_id;
    }
    public function toggleCategoryVisibility()
    {
        $this->isVisible = !$this->isVisible;
        $this->msg = null;
        
    }

    public function closeVisible(){
        $this->isVisible = false;
    }
    public function toggleEditMode(){
        $this->isEdit = !$this->isEdit;
        $this->msg = null;
    }

    public function changeCategory($category_id){
        $this->category_id = $category_id;
        if($category_id){
            if($this->form_id){
                if($this->near_miss_table == true){
                    $form = near_miss_manager::find($this->form_id);
                }
                else{
                    $form = Form::where('id',$this->form_id)->first();
                }
                $form->be_spoke_form_category_id = $category_id;
                $form->save();
                $this->category = BeSpokeFormCategory::find($form->be_spoke_form_category_id);
                $this->emit('categoryUpdated',$this->category_id);
            }else{
                $this->category = BeSpokeFormCategory::find($this->category_id);
                $this->emit('categoryUpdated',$this->category_id);
            }
        }
    }

    public function addNewCategory(){
        $newCategory = new BeSpokeFormCategory();
        $user = Auth::guard('web')->user()->selected_head_office;
        $newCategory->reference_type = 'head_office';
        $newCategory->reference_id = $user->id;
        $newCategory->name = 'New Category';
        $newCategory->save();
        if($this->form_id){
            if($this->near_miss_table == true){
                $nearMissManager = near_miss_manager::where('id', $this->form_id)->first();
                if ($nearMissManager && $nearMissManager->be_spoke_form_category_id) {
                    $this->categories = BeSpokeFormCategory::where('reference_id', $user->id)->where('id',$nearMissManager->be_spoke_form_category_id)->get();
                } else {
                    $this->categories = BeSpokeFormCategory::where('reference_id', $user->id)->get();
                }
            }else{
                $this->categories = BeSpokeFormCategory::where('reference_id', $user->id)->get();
            }
        }else{
            $this->categories = BeSpokeFormCategory::where('reference_id', $user->id)->get();
        }
        foreach ($this->categories as $cat) {
            
            $this->category_input[$cat->id] = $cat->name ?? '';
        }
    }

    public function updateCategoryName($category_id){
        $inputValue = $this->category_input[$category_id] ?? 'New Category';
        $updatedCategory = BeSpokeFormCategory::find($category_id);
        $updatedCategory->name = $inputValue;
        $updatedCategory->save();
    }
    public function updateCategoryColor($category_id){
        $inputValue = $this->color_input[$category_id] ?? '#000';
        $updatedCategory = BeSpokeFormCategory::find($category_id);
        $updatedCategory->color = $inputValue;
        $updatedCategory->save();
    }

    // public function removeCategory($category_id)
    // {
    //     $headOffice = Auth::guard('web')->user()->selected_head_office;
    //     $delCategory = BeSpokeFormCategory::find($category_id);
    //     $delNearMiss = near_miss_manager::where('be_spoke_form_category_id', $category_id)->exists();
        
    //     $isCategoryInUser = false;
    
    //     if ($this->form_id) {
    //         if ($this->near_miss_table == true) {
    //             $nearMissManager = near_miss_manager::find($this->form_id);
    //             $isCategoryInUser = $nearMissManager && $nearMissManager->be_spoke_form_category_id == $category_id;
    //         } else {
    //             $forms = $headOffice->be_spoke_forms->pluck('be_spoke_form_category_id');
    //             $isCategoryInUser = $forms->contains($category_id);
    //         }
    //     }
    
    //     if ($isCategoryInUser || $delNearMiss) {
    //         $this->msg = 'Category in use. Choose another for deletion.';
    //     } elseif ($delCategory) {
    //         $delCategory->delete();
    //         $this->msg = 'Category deleted successfully.';
    //     } else {
    //         $this->msg = 'Category not found.';
    //     }
    
    //     $this->categories = BeSpokeFormCategory::where('reference_id', $headOffice->id)->get();
    // }

    public function removeCategory($category_id)
{
    $headOffice = Auth::guard('web')->user()->selected_head_office;
    $delCategory = BeSpokeFormCategory::find($category_id);
    
    if ($delCategory && $delCategory->name === 'General') {
        $this->msg = 'Cannot delete the "General" tag.';
        return;
    }

    $delNearMiss = near_miss_manager::where('be_spoke_form_category_id', $category_id)->exists();
    $isCategoryInUse = Form::where('be_spoke_form_category_id', $category_id)->exists();

    if ($delNearMiss || $isCategoryInUse) {
        $this->msg = 'Category is in use and cannot be deleted.';
    } elseif ($delCategory) {
        $delCategory->delete();
        $this->msg = 'Category deleted successfully.';
    } else {
        $this->msg = 'Category not found.';
    }

    $this->categories = BeSpokeFormCategory::where('reference_id', $headOffice->id)->get();
}
    
    public function render()
    {
        return view('livewire.category-manager');
    }
}
