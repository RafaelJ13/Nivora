<?php $pager->setSurroundCount(2); ?>

<nav aria-label="Paginação" class="n-pager">
    <ul class="n-pager-list">

        <?php if ($pager->hasPrevious()) : ?>
            <li>
                <a href="<?= $pager->getFirst() ?>" class="n-pager-link n-pager-arrow" aria-label="Primeira">
                    <i class="bi bi-chevron-double-left"></i>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getPrevious() ?>" class="n-pager-link n-pager-arrow" aria-label="Anterior">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
        <?php else : ?>
            <li>
                <span class="n-pager-link n-pager-arrow n-pager-disabled">
                    <i class="bi bi-chevron-double-left"></i>
                </span>
            </li>
            <li>
                <span class="n-pager-link n-pager-arrow n-pager-disabled">
                    <i class="bi bi-chevron-left"></i>
                </span>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <a href="<?= $link['uri'] ?>" class="n-pager-link <?= $link['active'] ? 'n-pager-active' : '' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li>
                <a href="<?= $pager->getNext() ?>" class="n-pager-link n-pager-arrow" aria-label="Próxima">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getLast() ?>" class="n-pager-link n-pager-arrow" aria-label="Última">
                    <i class="bi bi-chevron-double-right"></i>
                </a>
            </li>
        <?php else : ?>
            <li>
                <span class="n-pager-link n-pager-arrow n-pager-disabled">
                    <i class="bi bi-chevron-right"></i>
                </span>
            </li>
            <li>
                <span class="n-pager-link n-pager-arrow n-pager-disabled">
                    <i class="bi bi-chevron-double-right"></i>
                </span>
            </li>
        <?php endif ?>

    </ul>
</nav>