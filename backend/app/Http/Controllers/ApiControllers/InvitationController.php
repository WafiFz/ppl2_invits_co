<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invitation;
use Illuminate\Support\Facades\Validator;

class InvitationController extends Controller
{
    public function index()
    {
        try {
            $invitations = Invitation::all();

            $data = [
                'invitations' => $invitations,
            ];

            return $this->jsonResponse($data, 'Invitations retrieved successfully');
        } catch (\Exception $e) {
            return $this->jsonResponse(null, 'Failed to retrieve invitations', [$e->getMessage()], false, 500);
        }
    }

    public function show($id)
    {
        try {
            $invitation = Invitation::with(
                'type', 
                'wedding', 
                'wedding.groom',
                'wedding.bride',
                'wedding.wish',
                'wedding.gift',
                'wedding.event',
                'wedding.love_story',
                'wedding.gallery',
            )->find($id);

            if (!$invitation) {
                return $this->jsonResponse(null, 'Invitation not found', [], false, 404);
            }

            $data = [
                'invitation' => $invitation,
            ];

            return $this->jsonResponse($data, 'Invitation retrieved successfully');
        } catch (\Exception $e) {
            return $this->jsonResponse(null, 'Failed to retrieve the invitation', [$e->getMessage()], false, 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:ACTIVE,INACTIVE,INCOMPLETE',
            'slug' => 'nullable|unique:invitations',
            'is_custom_domain' => 'required|boolean',
            'custom_domain' => 'nullable',
            'user_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:orders,id',
            'invitation_type_id' => 'required|exists:invitation_types,id',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Validation error', $validator->errors(), false, 422);
        }

        try {
            $invitation = Invitation::create($request->all());

            $data = [
                'invitation' => $invitation,
            ];

            return $this->jsonResponse($data, 'Invitation created successfully', [], true, 201);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, 'Failed to create the invitation', [$e->getMessage()], false, 500);
        }
    }

    public function update(Request $request, $id)
    {
        $invitation = Invitation::find($id);

        if (!$invitation) {
            return $this->jsonResponse(null, 'Invitation not found', [], false, 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'nullable|in:ACTIVE,INACTIVE,INCOMPLETE|filled',
            'slug' => 'nullable|filled|unique:invitations,slug,' . $id,
            'is_custom_domain' => 'nullable|filled|boolean',
            'custom_domain' => 'nullable|filled',
            'user_id' => 'nullable|filled|exists:users,id',
            'order_id' => 'nullable|filled|exists:orders,id',
            'invitation_type_id' => 'nullable|filled|exists:invitation_types,id',
        ]);
        

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Validation error', $validator->errors(), false, 422);
        }

        // Filter out null and empty values from the request data
        $filteredData = array_filter($request->all(), function ($value) {
            return !is_null($value) && $value !== '';
        });

        try {
            $invitation->update($request->all());

            $data = [
                'invitation' => $invitation,
            ];

            return $this->jsonResponse($data, 'Invitation updated successfully');
        } catch (\Exception $e) {
            return $this->jsonResponse(null, 'Failed to update the invitation', [$e->getMessage()], false, 500);
        }
    }

    public function destroy($id)
    {
        $invitation = Invitation::find($id);

        if (!$invitation) {
            return $this->jsonResponse(null, 'Invitation not found', [], false, 404);
        }

        try {
            $invitation->delete();
            return $this->jsonResponse(null, 'Invitation deleted successfully');
        } catch (\Exception $e) {
            return $this->jsonResponse(null, 'Failed to delete the invitation', [$e->getMessage()], false, 500);
        }
    }
}
