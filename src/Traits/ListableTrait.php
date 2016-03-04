<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 04.03.16
 * Time: 11:32
 */

namespace Nebo15\REST\Traits;

use Nebo15\REST\Exceptions\TraitException;

/**
 * Class Nebo15\RESTListable
 * @package App\Http\Traits
 * @property array $listable
 */
trait ListableTrait
{
    abstract public function toArray();

    public function toListArray()
    {
        if (property_exists($this, 'listable') and $this->listable) {
            if (!is_array($this->listable)) {
                throw new TraitException("Property \$listable shoul be an array in " . get_class($this));
            }

            return $this->listable;
        } else {
            return $this->toArray();
        }
    }
}
