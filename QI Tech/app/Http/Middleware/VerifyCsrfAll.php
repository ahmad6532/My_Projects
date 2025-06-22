<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Http\Request;

class VerifyCsrfAll extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification for GET requests.
     *
     * @var array
     */
    protected $getExcept = [
        'user.delete.contact',
        'location.delete_drafts',
        'location.near_miss.delete',
        'location.near_miss.delete_near',
        'location.patient_safety_alert_delete_comment',
        'location.be_spoke_form_category.delete', // not used in view
        'be_spoke_forms_templates.form_stage_delete',
        'be_spoke_forms_templates.form_group_delete',
        'be_spoke_forms_templates.form_stage_questions.delete',
        'be_spoke_forms_templates.form_stage_questions.action.condition.delete',
        'be_spoke_forms_templates.form_stage_questions.email.attachment.delete',
        'be_spoke_forms.be_spoke_form.delete',
        'locations.location.destroy',
        'service_messages.service_message.destroy',
        'national_alerts.national_alert.destroy',
        'head_offices.head_office.destroy',
        'users.user.destroy',
        'head_office.case.default_request_information_text.delete',
        'be_spoke_form_categories.be_spoke_form_category.delete',
        'head_office.be_spoke_form.form_card_delete',
        'form_template.calender.event_delete',
        'head_office.be_spoke_forms.be_spoke_form.delete',
        'head_office.be_spoke_forms_templates.form_stage_delete',
        'head_office.be_spoke_form.stage.default_task_delete',
        'default_links.default_link_delete',
        'default_documents.default_document.delete',
        'head_office.be_spoke_forms_templates.form_stage_questions.delete',
        'head_office.be_spoke_forms_templates.form_group_delete',
        'head_office.be_spoke_forms_templates.form_stage_questions.email.attachment.delete',
        'head_office.be_spoke_forms_templates.form_stage_questions.action.condition.delete',
        'organisation_settings.organisation_setting.delete',
        'head_office.tag_category_delete',
        'head_office.orginisation.delete_tag',
        'head_office.organisation.group.delete',
        'head_office.organisation.delete_group',
        'head_office.organisation.assign_tags.delete',
        'location.delete.update.multi',
        'users.delete_drafts',
        'head_office.head_office_profile_delete',
        'head_office.head_office_access_right_delete',
        'head_office.approved_location.delete',
        'head_office.contact.delete_relation',
        'head_office.contact.add_new_contact_delete',
        'head_office.contact.add_new_normal_address_delete',
        'head_office.gdpr.delete',
        'head_office.contacts.delete_comment',
        'case_docuemnts.case_docuemnt.case_docuemnt_delete',
        'head_office.setting.default_fish_bone_question_delete',
        'head_office.setting.default_five_whys_question_delete',
        'head_office.update_near_miss.delete',
        'case_manager.delete_comment',
        'case_manager.delete_task',
        'links.link.delete',
        'head_office.statement.single_statement_delete',
        'share_emails.share_email.delete',
        // reject routes
        'head_office.request.request_rejected',
        'head_office.psa.reject',
        'head_office.case.share_case_reject',
        'case_manager.view.reject_case_close_request',
        // remove routes
        'locations.location.assign_manager_remove',
        'location.remove_action_patient_safety_alert',
        'location.document.removedHashed',
        'location.remove_pin',
        'user.share_case.request_extension_remove',
        'user.share_case.remove',
        'admin.location.remove_head_office',
        'head_offices.head_office.assign_super_admin_remove',
        'head_office.be_spoke_form.rule_remove',
        'head_office.removeLocations',
        'head_offices.head_office.remove_user',
        'head_office.remove_session_records',
        'links.link.removeable_links',
        'case_manager.remove_case_handler',
        'case_manager.remove_any_case_handler',
        'case_manager.remove_owner',
        // archive routes
        'head_office.psa.archive',
        'head_office.be_spoke_forms.be_spoke_form.archived',
        // active links action
        'head_office.tracking_link.active',
        'head_office.be_spoke_forms.near_miss.active',
        'case_docuemnts.case_docuemnt.case_docuemnt_activate',
        'default_links.default_link_activate',
        'head_office.be_spoke_forms.be_spoke_form.active',
        'users.user.activation_email',
        'users.user.toggle_active',
        'be_spoke_forms.be_spoke_form.active',
        //other actions
        'head_office.color_branding_get',
        'head_office.location.password_reset_link',
        'head_office.head_office_users.block_user',
        'case_manager.unseen_comment',
        'remove_case_access'
    ];

    
    public function handle($request, Closure $next)
    {
        // Check if the request is a GET request and not in the $getExcept array
        if ($request->isMethod('get') && $this->CheckInArray($request)) {
            if (!$this->tokensMatch($request)) {
                abort(403, 'CSRF token mismatch');
            }
        }

        return $next($request);
    }

    /**
     * Check if the request URI matches any in the custom GET except array.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        return in_array($request->path(), array_merge($this->except, $this->getExcept));
    }
    protected function CheckInArray($request)
    {
        return in_array($request->route()->getName(), $this->getExcept);
    }

    protected function tokensMatch($request)
    {
        // Check for token in query parameters or headers
        $token = $request->query('_token') ?: $request->header('X-CSRF-TOKEN');
        
        // Compare the token with the one stored in the session
        return $token && $token === $request->session()->token();
    }
}
