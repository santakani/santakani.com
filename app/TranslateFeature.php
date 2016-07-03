<?php

/*
 * This file is part of santakani.com
 *
 * (c) Guo Yunhe <guoyunhebrave@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use App;

use Illuminate\Database\Eloquent\Model;

use App\Localization\Languages;

/**
 * TranslateFeature
 *
 * Parent class for all models with translations. Contain methods to fetch and
 * update translations.
 *
 * @author Guo Yunhe <guoyunhebrave@gmail.com>
 * @see https://github.com/santakani/santakani.com/wiki/Translation
 */

trait TranslateFeature {

    /**
     * Get translations.
     *
     * Translation class must be named as:
     * Designer => DesignerTranslation
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function translations()
    {
        return $this->hasMany(get_class($this) . 'Translation');
    }

    /**
     * Get translated text from translations. Support fallback if language not
     * available.
     *
     * @param string $field  Field name, like "name", "tagline", "content".
     * @param string $locale Language code, optional. If not set, use English(en).
     * @return string
     */
    public function text($field, $locale = null) {
        if (!Languages::has($locale)) {
            $locale = App::getLocale();
        }

        $translation = $this->translations()->where('locale', $locale)->first();

        if (empty($translation->$field) || empty(trim(str_replace('&nbsp;', ' ', strip_tags($translation->$field))))) {
            if ($locale === 'en') {
                return '';
            } else {
                return $this->text($field, 'en');
            }
        } else {
            return $translation->$field;
        }
    }

    /**
     * Plain text of content with 200 character length.
     *
     * @return string
     */
    public function excerpt($field, $locale = null, $length = 200)
    {
        $plain_text = strip_tags($this->text($field, $locale));
        return grapheme_strlen($plain_text) > $length ? grapheme_substr($plain_text,0,$length) . '...' : $plain_text;
    }

}
