<?php declare(strict_types = 1);

namespace PHPStan\Rules\Comparison;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

class CompareUnequalVariablesRule implements \PHPStan\Rules\Rule
{
	public function getNodeType(): string
	{
		return Node\Expr\BinaryOp::class;
	}

	/**
	 * @param \PhpParser\Node\Expr\BinaryOp $node
	 * @param \PHPStan\Analyser\Scope $scope
	 * @return string[] errors
	 */
	public function processNode(Node $node, Scope $scope): array
	{
		if (!$node instanceof Node\Expr\BinaryOp\Identical && !$node instanceof Node\Expr\BinaryOp\NotIdentical) {
			return [];
		}

		if ($node->left->name === $node->right->name) {
			return [
				sprintf(
					'Useless comparison for same variable: $%s will always evaluate to %s.',
					$node->left->name,
					$node instanceof Node\Expr\BinaryOp\Identical ? 'true' : 'false'
				),
			];
		}

		return [];
	}
}
