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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Story
 *
 * Model for story page.
 *
 * @author Guo Yunhe <guoyunhebrave@gmail.com>
 * @see https://github.com/santakani/santakani.com/wiki/Story
 */
class Story extends Model
{
    use SoftDeletes;

    use ImageFeature;
    use TagFeature;
    use TranslateFeature;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'story';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Dynamic properties that should be included in toArray() or toJSON().
     *
     * @var array
     */
    protected $appends = ['title', 'tag_ids', 'image_ids'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image_id', 'user_id',
    ];

    //====================================================================
    // Relationship Methods
    //====================================================================

    /**
     * Owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    //====================================================================
    // Dynamic Properties
    //====================================================================

    /**
     * "title" getter.
     *
     * @return string
     */
    public function getTitleAttribute()
    {
        return $this->text('title');
    }

    /**
     * "excerpt" getter. Plain text of content with 200 character length.
     *
     * @return string
     */
    public function getExcerptAttribute()
    {
        $plain_text = strip_tags($this->text('content'));
        return grapheme_strlen($plain_text) > 200 ? grapheme_substr($plain_text,0,200) . '...' : $plain_text;
    }

    /**
     * "url" getter.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return url('story/' . $this->id);
    }

    /**
     * "edit_url" getter.
     *
     * @return string
     */
    public function getEditUrlAttribute()
    {
        return url('story/' . $this->id . '/edit');
    }

    //====================================================================
    // Other Methods
    //====================================================================



    //====================================================================
    // Static Functions
    //====================================================================


}
