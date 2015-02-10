<?php if ($paginator->getLastPage() > 1): ?>
<div class="table-pagination">
    <?php echo (new Dcpage($paginator))->pageString(); ?>
</div>
<?php endif; ?>