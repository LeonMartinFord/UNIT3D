<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public $timestamps = false;

    /**
     * Validation rules
     *
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required',
        'position' => 'required'
    ];

    /**
     * Has many torrents
     *
     *
     */
    public function torrents()
    {
        return $this->hasMany(Torrent::class);
    }

    /**
     * Has many requests
     *
     */
    public function requests()
    {
        return $this->hasMany(TorrentRequest::class);
    }

    /**
     * Has many recipes
     *
     */
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
