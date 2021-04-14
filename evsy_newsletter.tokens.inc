<?php


/**
 * Implements hook_token_info().
 */
function evsy_newsletter_token_info() {
    $type = [
        'name' => t('Comments'),
        'description' => t('Tokens for comments posted on the site.'),
        'needs-data' => 'comment',
    ];
    $comment['comment_node'] = [
        'name' => t("Node"),
        'description' => t("The node the comment was posted to."),
        'type' => 'node',
    ];
    $n = 0;
    return [
        'type' => ['comment' => $type],
        'tokens' => ['comment' => $comment],
    ];
}

function evsy_newsletter_tokens($type, $tokens, array $data, array $options, \Drupal\Core\Render\BubbleableMetadata $bubbleable_metadata) {
    $token_service = \Drupal::token();

    $replacements = [];

    if ($type == 'entity' && $data['entity_type'] === 'comment' && $data['token_type'] === 'comment') {
        /** @var \Drupal\comment\CommentInterface $comment */
        $comment = $data['entity'];
        $node_tokens = $token_service->findwithPrefix($tokens, 'comment_node');
        if ($node_tokens) {
            $node = $comment->getCommentedEntity();
            $replacements += $token_service->generate(
                'node',
                $node_tokens,
                ['node' => $node],
                $options,
                $bubbleable_metadata);
        }
    }

    return $replacements;
}
