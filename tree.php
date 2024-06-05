<?php

class TreeNode {
    public $value;
    public $left;
    public $right;
    public $height;

    public function __construct($value) {
        $this->value = $value;
        $this->left = null;
        $this->right = null;
        $this->height = 1;
    }
}

class Tree {
    private $root;

    public function __construct($value = null) {
        if ($value !== null) {
            $this->root = new TreeNode($value);
        } else {
            $this->root = null;
        }
    }

    public function insert($value) {
        $this->root = $this->insertRecursively($this->root, $value);
    }

    private function insertRecursively($node, $value) {
        if ($node === null) {
            return new TreeNode($value);
        }

        if ($value < $node->value) {
            $node->left = $this->insertRecursively($node->left, $value);
        } elseif ($value > $node->value) {
            $node->right = $this->insertRecursively($node->right, $value);
        } else {
            return $node;
        }

        $node->height = 1 + max($this->getHeight($node->left), $this->getHeight($node->right));

        return $this->balance($node);
    }

    public function search($value) {
        return $this->searchRecursively($this->root, $value);
    }

    private function searchRecursively($node, $value) {
        if ($node === null) {
            return false;
        }

        if ($value === $node->value) {
            return true;
        }

        if ($value < $node->value) {
            return $this->searchRecursively($node->left, $value);
        } else {
            return $this->searchRecursively($node->right, $value);
        }
    }

    public function print() {
        return $this->printRecursively($this->root, "", true);
    }

    private function printRecursively($node, $prefix, $isLeft) {
        $result = "";
        if ($node !== null) {
            $result .= $prefix;

            if ($isLeft) {
                $result .= "├── ";
            } else {
                $result .= "└── ";
            }

            $result .= $node->value . PHP_EOL;

            $result .= $this->printRecursively($node->left, $prefix . ($isLeft ? "│   " : "    "), true);
            $result .= $this->printRecursively($node->right, $prefix . ($isLeft ? "│   " : "    "), false);
        }
        return $result;
    }

    private function getHeight($node) {
        if ($node === null) {
            return 0;
        }
        return $node->height;
    }

    private function getBalance($node) {
        if ($node === null) {
            return 0;
        }
        return $this->getHeight($node->left) - $this->getHeight($node->right);
    }

    private function balance($node) {
        $balance = $this->getBalance($node);

        if ($balance > 1) {
            if ($this->getBalance($node->left) < 0) {
                $node->left = $this->rotateLeft($node->left);
            }
            return $this->rotateRight($node);
        }

        if ($balance < -1) {
            if ($this->getBalance($node->right) > 0) {
                $node->right = $this->rotateRight($node->right);
            }
            return $this->rotateLeft($node);
        }

        return $node;
    }

    private function rotateRight($y) {
        $x = $y->left;
        $T2 = $x->right;

        $x->right = $y;
        $y->left = $T2;

        $y->height = max($this->getHeight($y->left), $this->getHeight($y->right)) + 1;
        $x->height = max($this->getHeight($x->left), $this->getHeight($x->right)) + 1;

        return $x;
    }

    private function rotateLeft($x) {
        $y = $x->right;
        $T2 = $y->left;

        $y->left = $x;
        $x->right = $T2;

        $x->height = max($this->getHeight($x->left), $this->getHeight($x->right)) + 1;
        $y->height = max($this->getHeight($y->left), $this->getHeight($y->right)) + 1;

        return $y;
    }
}

session_start();

if (!isset($_SESSION['tree'])) {
    $_SESSION['tree'] = serialize(new Tree(10));
}

$tree = unserialize($_SESSION['tree']);

$action = $_GET['action'] ?? '';
$value = $_GET['value'] ?? '';

if ($action === 'insert' && is_numeric($value)) {
    $tree->insert((int)$value);
    $_SESSION['tree'] = serialize($tree);
    echo $tree->print();
} elseif ($action === 'search' && is_numeric($value)) {
    echo $tree->search((int)$value) ? 'Найдено' : 'Не найдено';
} elseif ($action === 'print') {
    echo $tree->print();
} elseif ($action === 'clear') {
    $_SESSION['tree'] = serialize(new Tree());
    echo "Дерево очищено.";
}

?>
