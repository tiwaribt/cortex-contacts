<?php

declare(strict_types=1);

namespace Cortex\Contacts\Http\Requests\Adminarea;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $data = $this->all();

        $data['entity_id'] = $this->user()->getKey();
        $data['entity_type'] = $this->user()->getMorphClass();

        $this->replace($data);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $contact = $this->route('contact') ?? app('rinvex.contacts.contact');
        $contact->updateRulesUniques();

        return $contact->getRules();
    }
}
