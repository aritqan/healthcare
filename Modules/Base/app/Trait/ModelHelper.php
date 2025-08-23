<?php

namespace Modules\Base\Trait;

trait ModelHelper {

    /**
     * return translation of the model if force is true it must return exactly the translation of the locale
     * if translation of the locale is not found it will return the first translation
     *
     * @param string|null $col
     * @param string|null $locale
     * @param bool $force
     * @return mixed
     */
    public function smartTrans(string|null $col = null, string|null $locale = null, bool $force = false) : mixed
    {
        $locale ??= app()->getLocale();

        if(! is_null($trans = $this->translations->where('locale', $locale)->first())) {
            if(! is_null($col)) {
                return $trans->{$col} ?? null;
            }

            return $trans;
        }

        if(! $force) {
            if(! is_null($trans = $this->translations->first())) {
                if(! is_null($col)) {
                    return $trans->{$col} ?? null;
                }

                return $trans;
            }
        }

        return null;
    }

    /**
     * Return Translation image url
     */
    public function transImageUrl(string $collection, string|null $locale = null, string $conversion = '', bool $force = false) : mixed
    {
        $locale ??= app()->getLocale();

        if(! is_null($trans = $this->translations->where('locale', $locale)->first())) {
            return $trans->getFirstMedia($collection)?->getUrl($conversion) ?? '';
        }

        if(! $force) {
            if(! is_null($trans = $this->translations->first())) {
                return $trans->getFirstMedia($collection)?->getUrl($conversion) ?? '';
            }
        }

        return null;
    }


    /**
     * If can view trash and the data has trash key and it's value is show
     *
     * @param array $data
     * @param string $permissionAction
     * @return bool
     */
    public function shouldShowTrash(array $data, string $permissionAction) : bool
    {
        return isset($data['trash']) && $data['trash'] == 'show' && (app('owner') || app('admin')->can($permissionAction));
    }

}
