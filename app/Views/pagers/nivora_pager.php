<?php $pager->setSurroundCount(2); ?>

<nav aria-label="Page navigation" class="d-flex justify-content-center my-4">
    <ul class="pagination pagination-nivora mb-0 align-items-center gap-2">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getFirst() ?>" class="page-link" aria-label="Primeira">
                    <span aria-hidden="true">&laquo;&laquo;</span>
                </a>
            </li>
            <li class="page-item">
                <a href="<?= $pager->getPrevious() ?>" class="page-link" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a href="<?= $link['uri'] ?>" class="page-link">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getNext() ?>" class="page-link" aria-label="Próxima">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <li class="page-item">
                <a href="<?= $pager->getLast() ?>" class="page-link" aria-label="Última">
                    <span aria-hidden="true">&raquo;&raquo;</span>
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav>