<?php
use Ramsey\Uuid\Uuid;

$formid = Uuid::uuid4()->toString();
$config = json_decode( file_get_contents( __DIR__ . "/../data/forms/" . $config . ".json"), true );
?>
<div class="opo-webhook-form-wrapper" data-formdata="{}">
    <?php
    $i = 0;
    foreach ($config["steps"] as $id => $step) :
    unset($title);
    unset($text);
    if (isset($step["title"])) {
        $title = "echo(" . $step["title"] . ");";
    }
    if (isset($step["text"])) {
        $text = "echo(" . $step["text"] . ");";
    }
    ?>
        <div class="opo-webhookform-step" data-step-id="<?=$formid?>-<?= $id ?>"<?= ($i > 0) ? " hidden" : ""?> data-step-type="<?= $step["type"] ?>">
        <?php
        if ($step["type"] == "form") :
        ?>
            <form class="opo-webhookform-form flex flex-wrap gap-x-6 gap-y-5" action="<?= $config["wh-url"] ?>" method="<?= $config["wh-method"] ?>" data-form-tag="<?= $config["wh-tag"] ?>">
            <?php
            foreach ($step["fields"] as $name => $field) :
                ?>
                <div class="opo-input-wrapper <?= $field["type"] ?><?= (isset($field["fullwidth"])) ? " fullwidth" : "" ?>">
                    <?php
                    if ($field["type"] == "checkbox") :
                    ?>
                    <input type="checkbox" hidden name="<?= $name ?>" id="<?= $name ?>-<?=$formid?>" class="<?= $name ?>" <?= (isset($field["checked"]) && $field["checked"] == true) ? " checked" : "" ?> >
                    <label for="<?= $name ?>-<?=$formid?>" class="block leading-tight"><?= $field["label"] ?></label>
                    <?php
                    elseif ($field["type"] == "textarea") :
                    ?>
                    <label for="<?= $name ?>-<?=$formid?>" class="text-xl"><?= $field["label"] ?></label>
                    <textarea name="<?= $name ?>" class="opo-textarea-autosize" id="<?= $name ?>-<?=$formid?>"<?= (isset($field["class"])) ? " class='{$field["class"]}'" : "" ?>></textarea>
                    <?php
                    elseif ($field["type"] == "uuid") :
                    ?>
                    <input type="hidden" name="<?= $name ?>" value="<?= $formid ?>">
                    <?php
                    elseif ($field["type"] == "submit") :
                    ?>
                    <div class="w-full flex">
                        <button type="submit" class="opo-button opo-button-next ml-auto"><?= $field["text"] ?></button>
                    </div>
                    <?php
                    elseif ($field["type"] == "helper") :
                    ?>
                    <p class="opo-form-helper text-xs"> <?= $field["content"] ?> </p>
                    <?php
                    else:
                    ?>
                    <label for="<?= $name ?>-<?=$formid?>" class="text-xl"><?= $field["label"] ?></label>
                    <input type="<?= $field["type"]?>" name="<?= $name ?>" id="<?= $name ?>-<?=$formid?>"<?= (isset($field["class"])) ? " class='{$field["class"]}'" : "" ?><?= (isset($field["placeholder"])) ? " placeholder='{$field["placeholder"]}'" : "" ?><?= (isset($field["required"]) && $field["required"] == true) ? " required" : "" ?><?= (isset($field["pattern"])) ? " pattern={$field["pattern"]}" : "" ?><?= (isset($field["value"])) ? " value={$field["value"]}" : "" ?>>
                    <?php
                    endif;
                    ?>
                </div>
                <?php
            endforeach;
            ?>
            </form>
        <?php
        elseif ($step["type"] == "select") :
        ?>
        <div class="opo-webhookform-select-wrapper">
            <div class="opo-webhookform-selection flex gap-6 mt-4" data-selection-id="<?= $step["selection"]["id"]?>">
            <?php
            foreach ($step["selection"]["choices"] as $key => $choice) :
            ?>
                <a href="#" class="opo-input-wrapper opo-button opo-webhookform-choice" <?= (isset($choice["mtag"])) ? " data-mtag='{$choice["mtag"]}'" : "" ?> data-value="<?= $key ?>"><?= $choice["label"] ?></a>
                <?php
            endforeach;
            ?>
            </div>
        </div>
        <?php
        elseif ($step["type"] == "redirect") :
            if (isset($step["params"])) {
                $params = json_encode($step["params"]);
            }
        ?>
        <div class="opo-webhookform-redirect" data-url="<?= $step["url"] ?>" data-target="<?= $step["target"] ?>" data-next="<?= $step["next"] ?>"<?= (isset($params)) ? " data-url-params='{$params}'" : "" ?>><em>Redirecting...</em></div>
        <?php
        elseif ($step["type"] == "thanksInterface") :
        ?>
        <div class="flex flex-wrap gap-2 mt-2 share-buttons" data-sharetext="<?= $step["sharetext"] ?>">
            <div class="ButtonWrapper">
                <a data-type="whatsapp" href="#" class="opo-button">Auf WhatsApp teilen</a>
            </div>
            <div class="ButtonWrapper">
                <a data-type="telegram" href="#" class="opo-button">Auf Telegram teilen</a>
            </div>
            <div class="ButtonWrapper">
                <a data-type="facebook" href="#" class="opo-button" >Auf Facebook teilen</a>
            </div>
            <div class="ButtonWrapper">
                <a data-type="email" href="#" class="opo-button opo-button-neg opo-button-line">Per Mail teilen</a>
            </div>
        </div>
        <?php
        endif;
        ?>
        </div>
    <?php
    $i++;
    endforeach;
    ?>
    <div class="opo-webhookform-step" data-step-id="<?=$formid?>-<?= $id ?>"<?= ($i > 0) ? " hidden" : ""?> data-step-type="thanksInterface">
        <p class="font-bold text-xl"><?= $config["thanksInterface"]["text"]?></p>
    </div>
</div>