<?php namespace Todaymade\Daux\Tree;

class Directory extends Entry
{
    protected $children = [];

    public function sort()
    {
        uasort($this->children, array($this, 'compareEntries'));
    }

    /**
     * @return Entry[]
     */
    public function getEntries()
    {
        return $this->children;
    }

    public function addChild(Entry $entry)
    {
        $this->children[$entry->getUri()] = $entry;
    }

    public function removeChild(Entry $entry)
    {
        unset($this->children[$entry->getUri()]);
    }

    private function compareEntries($a, $b)
    {
        $name_a = explode('_', $a->name);
        $name_b = explode('_', $b->name);
        if (is_numeric($name_a[0])) {
            $a = intval($name_a[0]);
            if (is_numeric($name_b[0])) {
                $b = intval($name_b[0]);
                if (($a >= 0) == ($b >= 0)) {
                    $a = abs($a);
                    $b = abs($b);
                    if ($a == $b) {
                        return (strcasecmp($name_a[1], $name_b[1]));
                    }
                    return ($a > $b) ? 1 : -1;
                }
                return ($a >= 0) ? -1 : 1;
            }
            $t = $name_b[0];
            if ($t && $t[0] === '-') {
                return -1;
            }
            return ($a < 0) ? 1 : -1;
        } else {
            if (is_numeric($name_b[0])) {
                $b = intval($name_b[0]);
                if ($b >= 0) {
                    return 1;
                }
                $t = $name_a[0];
                if ($t && $t[0] === '-') {
                    return 1;
                }
                return ($b >= 0) ? 1 : -1;
            }
            $p = $name_a[0];
            $q = $name_b[0];
            if (($p && $p[0] === '-') == ($q && $q[0] === '-')) {
                return strcasecmp($p, $q);
            } else {
                return ($p[0] === '-') ? 1 : -1;
            }
        }
    }

    public function dump()
    {
        $dump = parent::dump();

        foreach ($this->getEntries() as $entry) {
            $dump['children'][] = $entry->dump();
        }

        return $dump;
    }
}
