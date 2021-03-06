<?php

/*
 * This file is part of Santakani
 *
 * (c) Guo Yunhe <guoyunhebrave@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * Designer
 *
 * Model for designer page.
 *
 * @author Guo Yunhe <guoyunhebrave@gmail.com>
 * @see https://github.com/santakani/santakani.com/wiki/Designer
 */
class Designer extends Model
{
    use SoftDeletes;

    use Features\EditLockFeature;
    use Features\ImageFeature;
    use Features\LikeFeature;
    use Features\TagFeature;
    use Features\TransferFeature;
    use Features\TranslationFeature;
    use Searchable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'designer';

    /**
     * The attributes that are mass assignable. id, story_id, locale and timestamps
     * are protected from vulnerability.
     *
     * @var array
     */
    protected $fillable = [
        'image_id', 'logo_id', 'city_id', 'tag_ids', 'gallery_image_ids', 'email',
        'website', 'facebook', 'instagram', 'pinterest', 'youtube', 'vimeo',
        'vat_rate', 'national_shipment', 'regional_shipment', 'international_shipment',
        'free_shipment_over', 'address_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'locked_at'];

    /**
     * Dynamic properties that should be included in toArray() or toJSON().
     *
     * @var array
     */
    protected $appends = ['name', 'tagline', 'tag_ids', 'url'];

    /**
     * Children properties that should be transfered with parent. Key is property
     * name and value is boolean: if the child is a collection.
     *
     * @var array
     */
    protected $transfer_children = ['images' => true, 'designs' => true];

    //====================================================================
    // Management Methods
    //====================================================================

    /**
     * Soft delete with relationships.
     */
    public function deleteWithRelationships()
    {
        $this->delete();

        // Soft delete designs
        foreach ($this->designs as $design) {
            $design->deleteWithRelationships();
        }
    }

    /**
     * Restore with relationships.
     */
    public function restoreWithRelationships()
    {
        // Restore designs deleted at the same time
        foreach ($this->designs()->withTrashed()->get() as $design) {
            if ($this->deleted_at->lte($design->deleted_at)) {
                $design->restoreWithRelationships();
            }
        }

        $this->restore();
    }

    /**
     * Hard delete with relationships.
     */
    public function forceDeleteWithRelationships()
    {
        // Hard delete designs with relationships
        foreach ($this->designs()->withTrashed()->get() as $design) {
            $design->forceDeleteWithRelationships();
        }

        // Hard delete images with files
        foreach ($this->images as $image) {
            $image->deleteWithFiles();
        }

        // Hard delete likes
        $this->likes()->delete();

        // Detach tags
        $this->tags()->detach();

        $this->forceDelete();
    }

    ////////////////////////////////////////////////////////////////////////////
    //                                                                        //
    //                          Relationship Methods                          //
    //                                                                        //
    ////////////////////////////////////////////////////////////////////////////

    /**
     * Logo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function logo()
    {
        return $this->belongsTo('App\Image');
    }

    /**
     * Owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get country that the designer is located.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function country()
    {
        return $this->city->country();
    }

    /**
     * Get city that the designer is located.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function city()
    {
        return $this->belongsTo('App\City');
    }

    /**
     * Designs of the designer
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function designs()
    {
        return $this->hasMany('App\Design');
    }

    /**
     * Address for return products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function address()
    {
        return $this->belongsTo('App\Address');
    }


    ////////////////////////////////////////////////////////////////////////////
    //                                                                        //
    //                           Dynamic Properties                           //
    //                                                                        //
    ////////////////////////////////////////////////////////////////////////////

    /**
     * "name" getter.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->text('name');
    }

    /**
     * "tagline" getter.
     *
     * @return string
     */
    public function getTaglineAttribute()
    {
        return $this->text('tagline');
    }

    /**
     * "url" getter. URL of designer page.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return url('designer/' . $this->id);
    }



    ////////////////////////////////////////////////////////////////////////////
    //                                                                        //
    //                              Other Methods                             //
    //                                                                        //
    ////////////////////////////////////////////////////////////////////////////

    /**
     * Set editor's pick property. Save to database but won't touch timestamps.
     *
     * @param editor_pick
     */
    public function pick($editor_pick = true)
    {
        $timestamps = $this->timestamps;
        $this->timestamps = false;

        $this->editor_pick = $editor_pick;
        $this->save();

        $this->timestamps = $timestamps;
    }

    public function unpick()
    {
        $this->pick(false);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        // Load relationships
        $this->load('translations', 'tags.translations', 'city.translations', 'city.country.translations');

        // Generate array data
        $array = $this->toArray();

        // Customize array...

        return $array;
    }
}
