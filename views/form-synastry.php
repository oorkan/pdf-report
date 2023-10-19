<div class="pdfr-form-ctr">
    <form method="POST">
        <?= $nonceField ?>
        <div class="pdfr-form-group">
            <div class="pdfr-form-label el-required asterisk-right">
                <label for="pdfr-form-input-firstname" aria-label="Your First Name">Your First Name</label>
            </div>
            <?php
            $violation = printViolation("pdfr-form-input-firstname", $violations);
            $fieldValue = "";
            if (isset($_POST["pdfr-form-input-firstname"])) {
                $fieldValue = "value=\"" . esc_attr($_POST["pdfr-form-input-firstname"]) . "\"";
            }
            if (isset($_GET["firstname"])) {
                $fieldValue = "value=\"" . esc_attr($_GET["firstname"]) . "\"";
            }
            ?>
            <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
                <input class="pdfr-form-control"
                       type="text"
                       name="pdfr-form-input-firstname"
                       id="pdfr-form-input-firstname"
                       placeholder="Your First Name"
                       required
                       minlength="2"
                       <?= $fieldValue ?>
                >
                <?= $violation ?>
            </div>
        </div>
        <div class="pdfr-form-group">
            <div class="pdfr-form-label el-required asterisk-right">
                <label for="pdfr-form-input-lastname" aria-label="Your Last Name">Your Last Name</label>
            </div>
            <?php
            $violation = printViolation("pdfr-form-input-lastname", $violations);
            $fieldValue = "";
            if (isset($_POST["pdfr-form-input-lastname"])) {
                $fieldValue = "value=\"" . esc_attr($_POST["pdfr-form-input-lastname"]) . "\"";
            }
            if (isset($_GET["lastname"])) {
                $fieldValue = "value=\"" . esc_attr($_GET["lastname"]) . "\"";
            }
            ?>
            <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
                <input class="pdfr-form-control"
                       type="text"
                       name="pdfr-form-input-lastname"
                       id="pdfr-form-input-lastname"
                       placeholder="Your Last Name"
                       required
                       minlength="2"
                       <?= $fieldValue ?>
                >
                <?= $violation ?>
            </div>
        </div>
        <?php require_once "components/birthdate.php" ?>
        <div class="pdfr-form-group">
            <div class="pdfr-form-label el-required asterisk-right">
                <label for="pdfr-form-input-country" aria-label="Your Country of Birth">Your Country of Birth</label>
            </div>
            <?php
                $violation = printViolation("pdfr-form-input-country", $violations);
            ?>
            <div class="pdfr-form-el
                 <?= (printViolation("pdfr-form-input-country", $violations) !== "") ? "is-error" : "" ?>"
            >
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

        <hr style="margin: 30px auto;display:block;">

        <div class="pdfr-form-group">
            <div class="pdfr-form-label el-required asterisk-right">
                <label for="pdfr-form-input-sfirstname" aria-label="Partner's First Name">Partner's First Name</label>
            </div>
            <?php
            $violation = printViolation("pdfr-form-input-sfirstname", $violations);
            $fieldValue = "";
            if (isset($_POST["pdfr-form-input-sfirstname"])) {
                $fieldValue = "value=\"" . esc_attr($_POST["pdfr-form-input-sfirstname"]) . "\"";
            }
            ?>
            <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
                <input class="pdfr-form-control"
                       type="text"
                       name="pdfr-form-input-sfirstname"
                       id="pdfr-form-input-sfirstname"
                       placeholder="Partner's First Name"
                       required
                       <?= $fieldValue ?>
                >
                <?= $violation ?>
            </div>
        </div>
        <div class="pdfr-form-group">
            <div class="pdfr-form-label el-required asterisk-right">
                <label for="pdfr-form-input-slastname" aria-label="Partner's Last Name">Partner's Last Name</label>
            </div>
            <?php
            $violation = printViolation("pdfr-form-input-slastname", $violations);
            $fieldValue = "";
            if (isset($_POST["pdfr-form-input-slastname"])) {
                $fieldValue = "value=\"" . esc_attr($_POST["pdfr-form-input-slastname"]) . "\"";
            }
            ?>
            <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
                <input class="pdfr-form-control"
                       type="text"
                       name="pdfr-form-input-slastname"
                       id="pdfr-form-input-slastname"
                       placeholder="Partner's Last Name"
                       required
                       <?= $fieldValue ?>
                >
                <?= $violation ?>
            </div>
        </div>
        <?php require_once "components/sbirthdate.php"; ?>
        <div class="pdfr-form-group">
            <div class="pdfr-form-label el-required asterisk-right">
                <label for="pdfr-form-input-scountry" aria-label="Partner's Country of Birth">Partner's Country of Birth</label>
            </div>
            <?php
                $violation = printViolation("pdfr-form-input-scountry", $violations);
            ?>
            <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
                <select class="pdfr-form-control"
                        id="pdfr-form-input-scountry"
                        name="pdfr-form-input-scountry"
                        required
                >
                    <?php $isDefaultSelected = (!isset($_POST["pdfr-form-input-scountry"]) ? "selected" : ""); ?>
                    <option value="" disabled <?= $isDefaultSelected ?>>Select Country</option>
                    <?= printCountries("pdfr-form-input-scountry") ?>
                </select>
                <?= $violation ?>
            </div>
        </div>
        <div class="pdfr-form-group">
            <div class="pdfr-form-label el-required asterisk-right">
                <label for="pdfr-form-input-scity" aria-label="Partner's Town or City of Birth">Partner's Town or City of Birth</label>
            </div>
            <?php
            $violation = printViolation("pdfr-form-input-scity", $violations);
            $fieldValue = "";
            if (isset($_POST["pdfr-form-input-scity"])) {
                $fieldValue = "value=\"" . esc_attr($_POST["pdfr-form-input-scity"]) . "\"";
            }
            ?>
            <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
                <input class="pdfr-form-control"
                       type="text"
                       placeholder="Partner's City"
                       name="pdfr-form-input-scity"
                       id="pdfr-form-input-scity"
                       required
                       <?= $fieldValue ?>
                >
                <?= $violation ?>
            </div>
        </div>
        <div class="pdfr-form-group">
            <div class="pdfr-form-label">
                <label for="pdfr-form-input-sstate" aria-label="Partner's State of Birth">Partner's State of Birth</label>
            </div>
            <?php
            $violation = printViolation("pdfr-form-input-sstate", $violations);
            $fieldValue = "";
            if (isset($_POST["pdfr-form-input-sstate"])) {
                $fieldValue = "value=\"" . esc_attr($_POST["pdfr-form-input-sstate"]) . "\"";
            }
            ?>
            <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
                <input class="pdfr-form-control"
                       type="text"
                       placeholder="Partner's State"
                       name="pdfr-form-input-sstate"
                       id="pdfr-form-input-sstate"
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
