<div class="tracking">
    <h2>Tracking</h2>
    <table class="table table-striped">
        <thead>
            <tr> <th>Tracking</th> <th>Tracking Information</th> <th>Weight</th> <th>Ship Date</th> </tr>
        </thead>
        <tbody>
            <?php $trackings = getordertracking(session_id(), $ordn, false); ?>
            <?php foreach($trackings as $tracking) : ?>
            	<?php $carrier = $tracking['servtype']; $link = ""; $link = gettracklink($tracking['servtype'], $tracking['tracknbr']); ?>
                <tr class="tracking">
                    <td><b>Shipped:</b>  <?php echo $carrier; ?></td>
                    <td><b>Tracking No.:</b>
                        <?php if ($link == "#$ordn" ): ?>
                            <?php echo $tracking['tracknbr']; ?>
                        <?php else : ?>
                            <b><a href="<?php echo $link; ?>"target="_blank" title="Click To Track"><?php echo $tracking['tracknbr']; ?></a></b>
                        <?php endif; ?>
                    </td>
                    <td><b>Weight: </b><?php echo $tracking['weight']; ?></td>
                    <td><b>Ship Date: </b><?php echo $tracking['shipdate']; ?> </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
