<?php

namespace App\Http\Resources;

use App\Models\Staff;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class RequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $staff = Staff::where("staffno", $this->requestor)->where("school_code", $this->school_code)
            ->where("deleted", 0)->first();

        $delStaff = Staff::where("staffno", $this->delivered_by)->where("school_code", $this->school_code)
            ->where("deleted", 0)->first();

        return [
            "id" => $this->transid,
            "item" => $this->item_desc,
            "semester" => $this->sem_desc,
            // "acterm" => $this->term,
            "staff" => empty($staff) ? $this->requestor : "{$staff->fname} {$staff->mname} {$staff->lname}",
            "req_quantity" => $this->requested_quantity,
            "req_date" => date("jS M Y", strtotime($this->requested_date)),
            "del_date" => $this->delivery_date ? date("jS M Y", strtotime($this->delivery_date)) : "___",
            // "del_date" => $this->delivery_date,
            "del_staff" => empty($delStaff) ? ($this->delivered_by ? $this->delivered_by : "___") : "{$delStaff->fname} {$delStaff->mname} {$delStaff->lname}",
            "del_quantity" => $this->delivered_quantity ? $this->delivered_quantity : "___",
            "status" => !$this->status ? "<span class='badge badge-pill badge-warning'>Requested</span>" : "<span class='badge badge-pill badge-success'>Delivered</span>",
            "action" => <<<EOT
            <button class='btn btn-sm btn-outline-info rounded info-btn' 
            title='expense details'>
            <i class='fas fa-info'></i>
            </button>
            <button class='btn btn-sm btn-outline-danger rounded delete-btn'>
                <i class='fas fa-trash'></i>
            </button>
            EOT
        ];
    }
}
