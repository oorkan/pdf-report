<div class="pdfr-form-ctr">
    <form method="POST">
        <?= $nonceField ?>
        <div class="pdfr-form-group">
            <div class="pdfr-form-label el-required asterisk-right">
                <label for="pdfr-form-input-name" aria-label="Your Name">Your Name</label>
            </div>
            <?php
            $violation = printViolation("pdfr-form-input-name", $violations);
            $fieldValue = "";
            if (isset($_POST["pdfr-form-input-name"])) {
                $fieldValue = "value=\"" . esc_attr($_POST["pdfr-form-input-name"]) . "\"";
            }
            if (isset($_GET["firstname"]) && isset($_GET["lastname"])) {
                $fieldValue = "value=\"" . esc_attr($_GET["firstname"] . " " . $_GET["lastname"]) . "\"";
            }
            ?>
            <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
                <input class="pdfr-form-control"
                       type="text"
                       name="pdfr-form-input-name"
                       id="pdfr-form-input-name"
                       placeholder="Name"
                       required
                       minlength="2"
                       <?= $fieldValue ?>
                >
                <?= $violation ?>
            </div>
        </div>
        <?php require_once "components/birthdate.php"; ?>
        <div class="pdfr-form-group">
            <div class="pdfr-form-label el-required asterisk-right">
                <label for="pdfr-form-input-country" aria-label="Your Country of Birth">Your Country of Birth</label>
            </div>
            <?php
                $violation = printViolation("pdfr-form-input-country", $violations);
            ?>
            <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
                <select class="pdfr-form-control"
                        id="pdfr-form-input-country"
                        name="pdfr-form-input-country"
                        required
                >
                    <?php $isDefaultSelected = (!isset($_POST["pdfr-form-input-country"]) ? "selected" : ""); ?>
                    <option value="" disabled <?= $isDefaultSelected ?>>Select Country</option>
                    <?= printCountries("pdfr-form-input-country") ?>
                </select>
                <?= $violation ?>
            </div>
        </div>
        <div class="pdfr-form-group">
            <div class="pdfr-form-label el-required asterisk-right">
                <label for="pdfr-form-input-city" aria-label="Your Town or City of Birth">Your Town or City of Birth</label>
            </div>
            <?php
            $violation = printViolation("pdfr-form-input-city", $violations);
            $fieldValue = "";
            if (isset($_POST["pdfr-form-input-city"])) {
                $fieldValue = "value=\"" . esc_attr($_POST["pdfr-form-input-city"]) . "\"";
            }
            if (isset($_GET["city"])) {
                $fieldValue = "value=\"" . esc_attr($_GET["city"]) . "\"";
            }
            ?>
            <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
                <input class="pdfr-form-control"
                       type="text"
                       placeholder="City"
                       name="pdfr-form-input-city"
                       id="pdfr-form-input-city"
                       required
                       <?= $fieldValue ?>
                >
                <?= $violation ?>
            </div>
        </div>
        <div class="pdfr-form-group">
            <div class="pdfr-form-label">
                <label for="pdfr-form-input-state" aria-label="Your State of Birth">Your State of Birth</label>
            </div>
            <?php
            $violation = printViolation("pdfr-form-input-state", $violations);
            $fieldValue = "";
            if (isset($_POST["pdfr-form-input-state"])) {
                $fieldValue = "value=\"" . esc_attr($_POST["pdfr-form-input-state"]) . "\"";
            }
            if (isset($_GET["state"])) {
                $fieldValue = "value=\"" . esc_attr($_GET["state"]) . "\"";
            }
            ?>
            <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
                <input class="pdfr-form-control"
                       type="text"
                       placeholder="State"
                       name="pdfr-form-input-state"
                       id="pdfr-form-input-state"
                       <?= $fieldValue ?>
                >
                <?= $violation ?>
            </div>
        </div>
        <div class="pdfr-form-group text-center">
            <button type="submit" class="pdfr-form-submit" name="pdfr-form-submit">submit »</button>
        </div>
    </form>
</div>
