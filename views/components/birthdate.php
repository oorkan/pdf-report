<?php

$months = [
    1  => 'January',
    2  => 'February',
    3  => 'March',
    4  => 'April',
    5  => 'May',
    6  => 'June',
    7  => 'July',
    8  => 'August',
    9  => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December'
];

?>

<div class="pdfr-form-group">
    <div class="pdfr-form-label el-required asterisk-right">
        <label for="pdfr-form-input-year" aria-label="Your Date of Birth">Your Date of Birth</label>
    </div>
    <div class="pdfr-form-group-inner">
        <?php
        $isDefaultSelected = (!isset($_POST["pdfr-form-input-year"]) ? "selected" : "");
        $violation = printViolation("pdfr-form-input-year", $violations);
        ?>
        <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
            <select class="pdfr-form-control" name="pdfr-form-input-year" id="pdfr-form-input-year" required>
                <option value="" disabled <?= $isDefaultSelected ?>>Year</option>
            <?php
            for ($year = (int) date('Y'); $year >= 1900; $year--) {
                $isSelectedYear = "";
                if (isset($_POST["pdfr-form-input-year"]) && (int) $_POST["pdfr-form-input-year"] === $year) {
                    $isSelectedYear = "selected";
                }

                echo <<<HTML
                <option value="{$year}" {$isSelectedYear}>{$year}</option>
                HTML;
            }
            ?>
            </select>
            <?= $violation ?>
        </div>
        <?php
            $isDefaultSelected = (!isset($_POST["pdfr-form-input-month"]) ? "selected" : "");
            $violation = printViolation("pdfr-form-input-month", $violations);
        ?>
        <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
            <select class="pdfr-form-control" name="pdfr-form-input-month" id="pdfr-form-input-month" required>
                <option value="" disabled <?= $isDefaultSelected ?>>Month</option>
            <?php
            foreach ($months as $month => $monthName) {
                $isSelectedMonth = "";
                if (isset($_POST["pdfr-form-input-month"]) && (int) $_POST["pdfr-form-input-month"] === $month) {
                    $isSelectedMonth = "selected";
                }

                echo <<<HTML
                <option value="{$month}" {$isSelectedMonth}>{$monthName}</option>
                HTML;
            }
            ?>
            </select>
            <?= $violation ?>
        </div>
        <?php
            $isDefaultSelected = (!isset($_POST["pdfr-form-input-day"]) ? "selected" : "");
            $violation = printViolation("pdfr-form-input-day", $violations);
        ?>
        <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
            <?php
            $isSelectedDay = "";
            if (isset($_POST["pdfr-form-input-day"])) {
                $isSelectedDay = "data-selected-day=\"" . $_POST["pdfr-form-input-day"] . "\"";
            }
            ?>
            <select class="pdfr-form-control"
                    name="pdfr-form-input-day"
                    id="pdfr-form-input-day"
                    required
                    disabled
                    <?= $isSelectedDay?>
            >
                <option value="" disabled <?= $isDefaultSelected ?>>Day</option>
            </select>
            <?= $violation ?>
        </div>
    </div>
</div>
<div class="pdfr-form-group">
    <div class="pdfr-form-label el-required asterisk-right">
        <label for="pdfr-form-input-hour" aria-label="Your Time of Birth">Your Time of Birth</label>
    </div>
    <div class="pdfr-form-group-inner">
        <?php
            $isDefaultSelected = (!isset($_POST["pdfr-form-input-hour"]) ? "selected" : "");
            $violation = printViolation("pdfr-form-input-hour", $violations);
        ?>
        <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
            <select class="pdfr-form-control" name="pdfr-form-input-hour" id="pdfr-form-input-hour" required>
                <option value="" disabled <?= $isDefaultSelected ?>>Hour</option>
            <?php
            for ($hour = 1; $hour <= 12; $hour++) {
                $optionText = str_pad($hour, 2, '0', STR_PAD_LEFT);
                $isSelectedHour = "";
                if (isset($_POST["pdfr-form-input-hour"]) && (int) $_POST["pdfr-form-input-hour"] === $hour) {
                    $isSelectedHour = "selected";
                }

                echo <<<HTML
                <option value="{$hour}" $isSelectedHour>{$optionText}</option>
                HTML;
            }
            ?>
            </select>
            <?= $violation ?>
        </div>
        <?php
            $isDefaultSelected = (!isset($_POST["pdfr-form-input-minute"]) ? "selected" : "");
            $violation = printViolation("pdfr-form-input-minute", $violations);
        ?>
        <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
            <select class="pdfr-form-control" name="pdfr-form-input-minute" id="pdfr-form-input-minute" required>
                <option value="" disabled <?= $isDefaultSelected ?>>Minute</option>
            <?php
            for ($minute = 0; $minute < 60; $minute++) {
                $optionText = str_pad($minute, 2, '0', STR_PAD_LEFT);
                $isSelectedMinute = "";
                if (isset($_POST["pdfr-form-input-minute"]) && (int) $_POST["pdfr-form-input-minute"] === $minute) {
                    $isSelectedMinute = "selected";
                }

                echo <<<HTML
                <option value="{$minute}" $isSelectedMinute>{$optionText}</option>
                HTML;
            }
            ?>
            </select>
            <?= $violation ?>
        </div>
        <?php
            $violation = printViolation("pdfr-form-input-meridiem", $violations);
        ?>
        <div class="pdfr-form-el <?= ($violation !== "") ? "is-error" : "" ?>">
            <select class="pdfr-form-control" name="pdfr-form-input-meridiem" id="pdfr-form-input-meridiem" required>
            <?php
            foreach (["am", "pm"] as $meridiem) {
                $isSelectedMeridiem = "";
                if (isset($_POST["pdfr-form-input-meridiem"]) && $_POST["pdfr-form-input-meridiem"] === $meridiem) {
                    $isSelectedMeridiem = "selected";
                }
                $optionText = strtoupper($meridiem);

                echo <<<HTML
                <option value="{$meridiem}" {$isSelectedMeridiem}>{$optionText}</option>
                HTML;
            }
            ?>
            </select>
            <?= $violation ?>
        </div>
        <div class="pdfr-form-el" style="flex-basis: 100%; margin-top: -12px;">
            <label style="user-select: none;" id="pdfr-form-input-birthtime-resolver-label">
                <input type="checkbox"
                       class="pdfr-form-control"
                       id="pdfr-form-input-birthtime-resolver"
                > I'm not sure...
            </label>
        </div>
    </div>
</div>
<script>
    ((dom, bom) => {
        const yearInput = document.querySelector("#pdfr-form-input-year");
        const monthInput = document.querySelector("#pdfr-form-input-month");
        const dayInput = document.querySelector("#pdfr-form-input-day");
        const hourInput = document.querySelector("#pdfr-form-input-hour");
        const minuteInput = document.querySelector("#pdfr-form-input-minute");
        const meridiemInput = document.querySelector("#pdfr-form-input-meridiem");
        const birthtimeResolverInput = document.querySelector("#pdfr-form-input-birthtime-resolver");

        const updateDayInput = (target, month, year) => {
            let daysInMonth = new Date(year, month, 0).getDate();
            let selectedDay = target.getAttribute("data-selected-day") ?? "";

            target.innerHTML = target.firstElementChild.outerHTML;
            target.removeAttribute("disabled");

            for (let day = 1; day <= daysInMonth; day++) {
                target.innerHTML += `<option value="${day}">${('' + day).padStart(2, "0")}</option`;
            }

            if (selectedDay) {
                target.querySelector("option[selected]")?.removeAttribute("selected");
                target.querySelector(`option[value="${selectedDay}"]`).selected = true;
                target.removeAttribute("data-selected-day");
            }
        }

        if (monthInput.value && yearInput.value) {
            updateDayInput(dayInput, monthInput.value, yearInput.value);
        }

        yearInput.onchange = e => {
            if (monthInput.value) {
                updateDayInput(dayInput, monthInput.value, e.target.value);
            }
        }

        monthInput.onchange = e => {
            if (yearInput.value) {
                updateDayInput(dayInput, e.target.value, yearInput.value);
            }
        }

        birthtimeResolverInput.onchange = e => {
            if (e.target.checked) {
                hourInput.querySelector("option[selected]")?.removeAttribute("selected");
                minuteInput.querySelector("option[selected]")?.removeAttribute("selected");

                hourInput.querySelector(`option[value="12"]`).selected = true;
                minuteInput.querySelector(`option[value="0"]`).selected = true;
                meridiemInput.querySelector(`option[value="am"]`).selected = true;
            }
        }

        hourInput.onchange = e => {
            birthtimeResolverInput.checked = false;
        }

        minuteInput.onchange = e => {
            birthtimeResolverInput.checked = false;
        }

        meridiemInput.onchange = e => {
            birthtimeResolverInput.checked = false;
        }
    })(document, window);
</script>
