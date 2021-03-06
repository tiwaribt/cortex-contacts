<?php

declare(strict_types=1);

namespace Cortex\Contacts\DataTables\Adminarea;

use Cortex\Contacts\Models\Contact;
use Illuminate\Database\Eloquent\Builder;
use Cortex\Foundation\DataTables\AbstractDataTable;
use Cortex\Contacts\Transformers\Adminarea\ContactTransformer;

class ContactsDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = Contact::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = ContactTransformer::class;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables($this->query())
            ->setTransformer(app($this->transformer))
            ->filterColumn('country_code', function (Builder $builder, $keyword) {
                $countryCode = collect(countries())->search(function ($country) use ($keyword) {
                    return mb_strpos($country['name'], $keyword) !== false || mb_strpos($country['emoji'], $keyword) !== false;
                });

                ! $countryCode || $builder->where('country_code', $countryCode);
            })
            ->filterColumn('language_code', function (Builder $builder, $keyword) {
                $languageCode = collect(languages())->search(function ($language) use ($keyword) {
                    return mb_strpos($language['name'], $keyword) !== false;
                });

                ! $languageCode || $builder->where('language_code', $languageCode);
            })
            ->make(true);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $link = config('cortex.foundation.route.locale_prefix')
            ? '"<a href=\""+routes.route(\'adminarea.contacts.edit\', {contact: full.id, locale: \''.$this->request->segment(1).'\'})+"\">"+data+"</a>"'
            : '"<a href=\""+routes.route(\'adminarea.contacts.edit\', {contact: full.id})+"\">"+data+"</a>"';

        return [
            'given_name' => ['title' => trans('cortex/contacts::common.given_name'), 'render' => $link, 'responsivePriority' => 0],
            'family_name' => ['title' => trans('cortex/contacts::common.family_name')],
            'email' => ['title' => trans('cortex/contacts::common.email')],
            'phone' => ['title' => trans('cortex/contacts::common.phone')],
            'country_code' => ['title' => trans('cortex/contacts::common.country'), 'render' => 'full.country_emoji+" "+data'],
            'language_code' => ['title' => trans('cortex/contacts::common.language'), 'visible' => false],
            'created_at' => ['title' => trans('cortex/contacts::common.created_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
            'updated_at' => ['title' => trans('cortex/contacts::common.updated_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
        ];
    }
}
