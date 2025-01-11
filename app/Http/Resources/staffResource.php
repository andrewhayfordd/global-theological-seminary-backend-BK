<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class staffResource extends JsonResource
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
            "name" => ucfirst("{$this->fname} {$this->mname} {$this->lname}"),
            "id" => $this->transid,
            "staffname" => $this->fname,
            "staffno" => $this->staffno,
            "stafftype" => $this->staff_type,
            "gender" => $this->gender,
            "staffemail" => $this->email,
            "stafflastname" => $this->lname,
            "staffmiddlename" => $this->mname,
            "staffdob" => $this->dob,
            "staffmaritalstatus" => $this->marital_status,
            "phone" => $this->phone,
            "email" => $this->email,
            "postaladdress" => $this->postal_address,
            "residentialaddress" => $this->residential_address,
            "stafftype" => $this->staff_type,
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
                        Staff Info
                </button>
                <button
                    class='dropdown-item btn btn-sm qual-btn mt-2'
                    data-toggle='modal' data-target='#add-qual-modal'
                    title='Add staff qualifications'>
                        <i class='fas fa-id-card-alt mr-1'></i>
                        Qualifications
                </button>

                    <button
                        class='dropdown-item btn btn-sm contact-btn mt-2' 
                        data-toggle='modal'
                        data-target='#add-contact-modal'
                        title='Add staff contacts'>
                            <i class='fas fa-phone mr-1'></i>
                            Contacts
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
