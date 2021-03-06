<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityLink extends Model
{
    //
    protected $fillable = [
      'channel_id',
      'title',
      'link'
    ];


    public static function from(User $user)
    {
      $link = new static;

      $link->user_id = $user->id;

      return $link;
    }

    public function contribute($attributes, $caller)
    {
      if ($existing = $this->hasAlreadyBeenSubmitted($attributes['link'])) {

          return $existing->touch();

          $caller->whenSpecialCircumstance();

      } else {

        return $this->fill($attributes)->save();

      }

    }

    public function scopeForChannel($builder, $channel)
    {
      if ($channel->id) {
        return $builder->where('channel_id', $channel->id);
      }

      return $builder;
    }

    public function creator()
    {
      return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
      return $this->belongsTo(Channel::class);
    }

    protected function hasAlreadyBeenSubmitted($link)
    {
      return static::where('link', $link)->first();
    }


}
