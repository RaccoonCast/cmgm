<p>Evidence Link: </p>

<input class="ci" maxlength="500" placeholder="Paste an image on the page to upload & submit." name="evidence_a" value="<?php if (isset($link)) echo $link?>"><br>
<?php if (isset($link)) {?> <a href="uploads/<?php echo $link;?>">Evidence</a><br> <?php } ?>
