<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePromotion extends Model
{
      // use HasFactory;
      protected $table = "emp_promotions";
      protected $fillable = [
          'company_id',
          'branch_id',
          'emp_id',
          'from_date',
          'emp_desig',
          'designation_id',
          'is_deleted',
          'desination_from',
          'designation_to',
          'is_approved'
      ];
      public function employee_detail(){
          return $this->belongsTo(EmployeeDetail::class,'emp_id','id');
      }
      public function company()
      {
          return $this->belongsTo(Company::class, 'company_id');
      }
  
      public function employee_approval(){
          return $this->hasOne(user_approval::class, 'emp_id','emp_id');
      }
      
      public function branch(){
          return $this->belongsTo(Location::class, 'branch_id','id');
      }
    
      public function designation(){
          return $this->belongsTo(Designation::class, 'designation_to','id');
      }
      public function preDesignation(){
          return $this->belongsTo(Designation::class, 'designation_from','id');
      }
}
