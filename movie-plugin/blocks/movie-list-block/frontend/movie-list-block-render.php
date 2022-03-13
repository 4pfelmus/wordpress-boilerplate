<?php
global $wpdb;
$movies = $wpdb->get_results(
    <<<SQL
        SELECT * 
        FROM {$wpdb->prefix}movie_entries as me
        LEFT JOIN (
            SELECT GROUP_CONCAT(presentation ORDER BY presentation ASC) as presentations, movie
            FROM {$wpdb->prefix}presentations
            GROUP BY movie
        ) as mp
        ON me.id = mp.movie
        ORDER BY created_at DESC;
    SQL
);
?>
<div class="wp-block-movie-plugin-movie-list-block">
    <?php foreach ($movies as $movie) { ?>
        <h4><?= $movie->title; ?></h4>
        <div><?= $movie->text; ?></div>
        <div class="wp-block-movie-url"><?= $movie->url; ?></div><!-- Link zum einzelnen Film? -->
        <?php if ($movie->image) { ?>
            <img src="<?= wp_get_attachment_image_src($movie->image)[0]; ?>" />
        <?php } ?>
        <?php foreach (explode(',', $movie->presentations) as $presentation) { ?>
            <div class="wp-block-movie-presentation"><?= $presentation; ?></div>
        <?php } ?>
    <?php } ?>
</div>

<div class="movie-list">
    <div class="movie-item">
        <div class="row">
            <div class="col-12 col-md-3">
                <div class="movie-banner">
                    <a href="<?= $movietrailer->url; ?>" target="_blank">
                        <?php if ($movie->image) { ?>
                            <img src="<?= wp_get_attachment_image_src($movie->image)[0]; ?>" />
                        <?php } ?>
                    </a>
                </div>
                <button type="button" class="btn btn-trailer">
                    Trailer
                </button>
            </div>
            <div class="col-12 col-md-9">
                <div class="movie-info">
                    <h3><?= $movie->title; ?></h3>
                    <div class="movie-screening-date">
                        <ul>
                            <?php if ($movie->screening1) { ?>
                            <li class="screening1">
                                <span class="date"><?= $movie->date1; ?></span> um <span class="time"><?= $movie->time1; ?></span>
                            </li>
                            <?php } ?>
                            <?php if ($movie->screening2) { ?>
                            <li class="screening2">
                                <span class="date"><?= $movie->date2; ?></span> um <span class="time"><?= $movie->time2; ?></span>
                            </li>
                            <?php } ?>
                            <?php if ($movie->screening3) { ?>
                            <li class="screening3">
                                <span class="date"><?= $movie->date3; ?></span> um <span class="time"><?= $movie->time3; ?></span>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>

                    <div class="movie-description">
                        <?php if ($movie->description) { ?>
                            <p><?= $movie->description; ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>