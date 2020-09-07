<?php $i = 0; ?>

<div class="gallery">
    <?php foreach ($entities as $keyEntity => $entity): ?>

        <?php if ($i % 2 == 0): ?><div class="row"><?php endif; ?>

            <div class="col-md-6 col-sm-6">
                <a class="gallery_link" href="<?php echo $entity[ 'image' ][ 'field_value' ]; ?>">
                    <div class="card_gallery">
                        <div class="overlay">
                            <div class="gallery_title">
                                <?php echo $entity[ 'title' ][ 'field_value' ]; ?>

                            </div>
                        </div>
                        <div class="gallery_img" style="background-image: url('<?php echo $entity[ 'image' ][ 'field_value' ]; ?>')"></div>
                    </div>
                </a>
            </div>
            <?php if ($i % 2 == 1): ?></div><?php endif; ?>
        <?php $i++; ?>
    <?php endforeach; ?>

</div>
