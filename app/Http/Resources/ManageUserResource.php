<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManageUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
              "userId"=>$this->userid,
              "Email"=>$this->email,
              "Phone"=>$this->phone,
              "userType"=>$this->usertype,
              "action" => " 
              <div class='dropdown'>
                  <button
                      class='btn btn-sm dropdown-toggle'
                      type='button'
                      id='dropdownMenu2'
                      data-toggle='dropdown'
                      aria-haspopup='true'
                      aria-expanded='false'>
                      <i class='fas fa-bars'></i>
                  </button>
  
                  <div class='dropdown-menu' aria-labelledby='actionMenuDropdown'>
                  
                  <button
                      class='dropdown-item btn btn-sm view-btn mt-2' 
                      data-toggle='modal'data-target='#viewPatientTestModal'
                      title='View staff info'>
                          <i class='fa fa-eye mr-1'></i>
                          User Info
                  </button>
                
  
                      <button
                          class='dropdown-item btn btn-sm edit-btn mt-2' 
                          data-toggle='modal'
                          data-target='#update-status-modal'
                          title='Update staff'>
                          <i class='fas fa-pencil-alt mr-1'></i>
                              Update
                      </button>
  
                      <button
                          class='dropdown-item btn btn-sm delete-btn mt-2'
                          title='Delete staff'>
                              <i class='fa fa-trash mr-1'></i>
                              Delete
                      </button>
                  </div>
              </div>
          ",
        ];
    }
}
