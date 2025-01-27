<script setup>
import { Link, useForm } from '@inertiajs/vue3';

defineProps({
    receipt: Object
})

function printPage() {
    window.print();
}

</script>

<style scoped>
body {
    padding: 10px;
    font-family: 'Times New Roman', Times, serif;
    color: black;
    background-color: white;
    font-size: 13px;
}

.receipt {
    margin: 0 auto;
    border: 2px solid black;
    border-radius: 20px;
}

.receipt-header,
.receipt-footer {
    text-align: center;

}

.receipt-title {
    font-weight: bold;
    font-size: 1.2rem;
    text-align: center;
    margin: 10px 0;
}

.receipt-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.receipt-info div {
    font-size: 0.9rem;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    font-size: 0.9rem;
}

.table th,
.table td {
    border: 1px solid #333;
    padding: 5px;
    text-align: center;
}

.table th {
    background-color: #f0f0f0;
}

.total-row {
    font-weight: bold;
}

.payment-method {
    margin-top: 20px;
    font-size: 0.9rem;
}

.payment-method div {
    display: inline-block;
    margin-right: 20px;
}

.note {
    font-size: 0.8rem;
    margin-top: 20px;
}

@media print {
    .buttons {
        display: none;
    }
}

.buttons {
    margin-bottom: 15px;
}

.buttons button, a {
    padding: 5px 15px;
    background-color: rgb(46, 123, 154);
    color: white;
    border-radius: 10px;
    margin-right: 5px;
    margin-top: 5px;
    margin-left: 5px;
    border: none;
    cursor: pointer;
    font-size: 12px;
    box-shadow: 2px 5px 5px rgb(180, 177, 177);
    display: flex;
    align-items: center;
    text-decoration: none;
}
</style>

<template>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Invoice Receipt</title>

    </head>

    <body>

        <div class="buttons">
            <div style="display: flex; align-items: center;">
                <a href="/">Back</a>
                <button @click="printPage">Print Receipt</button>
            </div>
        </div>

        <div v-for="(copy, index) in 2" :key="index">
            <div class="receipt">
                <table style="width: 100%; margin-bottom: 7px; ">
                    <tr>
                        <td style="border-bottom: 2px solid black;">
                            <center>
                                <img src="/logo/ph-logo.png" width="60" alt="">
                            </center>
                        </td>
                        <td style="border: 2px solid black; border-top: 0px;">
                            <center>
                                <p>Repubic of the Philippines</p>
                                <p>OFFICE OF THE PROVINCIAL TREASURER</p>
                                <p>Province of Southern Leyte</p>
                                <input type="text" value="SOGOD" readonly style="text-align: center; margin-bottom: 0; border-bottom: 1px solid black; font-weight: bold;">
                                <p>Municipality</p>
                            </center>
                        </td>
                        <td style="border: 2px solid black; border-top: 0px;">
                            <center>
                                <img src="/logo/province-logo.png" width="60" alt="">
                            </center>
                        </td>
                        <td style="border-top: none; border-right: 1px solid black;"></td>
                        <td
                            style="border: 2px solid black; border-bottom: 2px solid black; padding: 0; border-top: 0px; border-right: 0px;">
                            <table style="border-collapse: collapse; width: 100%; ">
                                <tr>
                                    <td
                                        style="background-color: black; color: white; padding: 10px; border-bottom: 1px solid black; text-align: center; width: 50%;">
                                        <small>OFFICIAL RECEIPT</small>
                                    </td>
                                    <td style="border-bottom: 1px solid black; padding: 10px; text-align: left;">
                                        <small>No. SL</small> <span
                                            style="font-weight: bold; font-size: 1.2em; padding-left: 5px;">{{ receipt.ORnumber }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="display: flex; align-items: center; padding: 10px 0px 10px;">
                                        <span style="display: inline-block;
                         width: 0;
                         height: 0;
                         border-top: 15px solid transparent;
                         border-bottom: 15px solid transparent;
                         border-left: 25px solid black;"></span>
                                        <small style="margin-left: 5px;">VALIDATION DATE</small>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>
                <table style="width: 100%; margin-bottom: 7px;">
                    <tr>
                        <td style="border: 1px solid black; border-left: 0px;" colspan="2"><small
                                style="margin-left: 10px;">PAYOR</small>
                            <div style="margin-left: 10px;">
                                {{ receipt.driverinfo.name }}
                            </div>
                        </td>
                        <td style="border: 1px solid black; padding-bottom: 15px;"><small
                                style="margin-left: 10px;">FUND</small></td>
                        <td style="border: 1px solid black; border-right: 0px;"><small
                                style="margin-left: 10px;">DATE</small>
                            <div style="margin-left: 10px;">
                                {{ formatDate(receipt.datePaid) }}
                            </div>
                        </td>
                    </tr>
                </table>

                <table style="width: 100%; margin-bottom: 7px;">
                    <tr>
                        <th style="border: 1px solid black; border-left: 0px; text-align: center;" colspan="2">
                            <small><b>NATURE OF
                                    COLLECTION</b></small></th>
                        <th style="border: 1px solid black; text-align: center"><small><b>ACCOUNT CODE</b></small></th>
                        <th style="border: 1px solid black; border-right: 0px; text-align: center">
                            <small><b>AMOUNT</b></small></th>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; border-left: 0px; text-align: center;" colspan="2">Penalty
                            Collection for Violation of Traffic Rules and Regulations</td>
                        <td style="border: 1px solid black; padding: 10px;"></td>
                        <td style="border: 1px solid black; border-right: 0px;">₱ {{ formatNumber(receipt.violationFee)
                            }}</td>
                    </tr>
                    <tr v-for="i in 3" :key="i">
                        <td style="border: 1px solid black; padding: 10px; border-left: 0px;" colspan="2"></td>
                        <td style="border: 1px solid black; padding: 10px;"></td>
                        <td style="border: 1px solid black; padding: 10px; border-right: 0px;"></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; padding: 0px; border-left: 0px;" colspan="2"><small><strong
                                    style="margin-left: 10px;">TOTAL</strong></small></td>
                        <td style="border: 1px solid black; padding: 10px;"></td>
                        <td style="border: 1px solid black; border-right: 0px;"><b>₱ {{
                                formatNumber(receipt.violationFee) }}</b></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; padding: 0px; border-left: 0px;" colspan="3"><small
                                style="margin-left: 10px;">AMOUNT IN WORDS</small><span
                                style="text-transform: uppercase; margin-left: 10px">{{
                                numberToWords(receipt.violationFee) }}</span></td>
                        <td style="border: 1px solid black; padding: 10px; border-right: 0px;"></td>
                    </tr>
                </table>

                <table style="width: 100%;">
                    <tr>
                        <td style="border: 1px solid black; border-bottom: 0px; padding: 10px; border-left: 0px;"
                            colspan="1">

                            <div>
                                <input type="checkbox" checked disabled>
                                <label for="" style="margin-left: 5px;">Cash</label>
                            </div>

                            <div>
                                <input type="checkbox">
                                <label for="" style="margin-left: 5px;">Check</label>
                            </div>

                            <div>
                                <input type="checkbox">
                                <label for="" style="margin-left: 5px;">Money Order</label>
                            </div>

                        </td>
                        <td style="border: 1px solid black; padding: 0px; border-bottom: 0px;">
                            <table style="width: 100%;">
                                <tr>
                                    <th
                                        style="border: 1px solid black; border-top: 0px; border-left: 0px; text-align: center">
                                        <small><b>DRAWEE
                                                BANK</b></small></th>
                                    <th style="border: 1px solid black; border-top: 0px; text-align: center">
                                        <small><b>NUMBER</b></small></th>
                                    <th
                                        style="border: 1px solid black; border-top: 0px; border-right: 0px; text-align: center">
                                        <small><b>DATE</b></small>
                                    </th>
                                </tr>
                                <tr>
                                    <td
                                        style="border: 1px solid black; padding-bottom: 80px; border-bottom: 0px; border-left: 0px;">
                                    </td>
                                    <td style="border: 1px solid black; padding-bottom: 80px; border-bottom: 0px;"></td>
                                    <td
                                        style="border: 1px solid black; padding-bottom: 80px; border-bottom: 0px; border-right: 0px;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="border: 1px solid black; padding: 10px; border-bottom: 0px; border-right: 0px;">
                            <p>Received the Amount</p>
                            <p>stated above</p>
                        </td>

                        <td style="border: 0px; border-top: 1px solid black; padding: 0px">
                            <center>
                                <input type="text"
                                    style="width: 95%; border-bottom: 1px solid black; text-align: center; font-size: 12px;"
                                    :value="receipt.treasurer" readonly><br>
                                <small>PROVINCIAL/MUNICIPAL TREASURER</small>
                                <br>
                                <input type="text"
                                    style="width: 95%; border-bottom: 1px solid black; text-align: center; font-size: 12px;"
                                    :value="receipt.collector" readonly><br>
                                <small>DEPUTY COLLECTOR</small>
                            </center>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="display: flex; justify-content: space-between; margin: 5px 10px 20px;">
                <p>NOTE: Write the number and date of this receipt on the back of check or money order
                    received.</p>
                <p><strong>{{ index === 0 ? 'ORIGINAL' : 'DUPLICATE' }}</strong></p>
            </div>
        </div>


    </body>

    </html>


</template>

<script>

function formatDate(dateString) {
    const options = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    };
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', options);
}

function formatNumber(value) {
    return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
}

function numberToWords(number) {
    const units = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
    const teens = ["", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
    const tens = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
    const thousands = ["", "Thousand", "Million", "Billion"];

    if (number === 0) return "Zero PESOS";

    const convertHundreds = (num) => {
        let str = "";
        if (num > 99) {
            str += units[Math.floor(num / 100)] + " Hundred ";
            num %= 100;
        }
        if (num > 10 && num < 20) {
            str += teens[num - 10] + " ";
        } else {
            str += tens[Math.floor(num / 10)] + " ";
            str += units[num % 10] + " ";
        }
        return str.trim();
    };

    const convertToWords = (num) => {
        let wordString = "";
        let i = 0;

        while (num > 0) {
            const chunk = num % 1000;
            if (chunk) {
                wordString = convertHundreds(chunk) + " " + thousands[i] + " " + wordString;
            }
            num = Math.floor(num / 1000);
            i++;
        }

        return wordString.trim();
    };

    // Split the number into pesos and centavos
    const pesos = Math.floor(number);
    const centavos = Math.round((number - pesos) * 100);

    let result = convertToWords(pesos) + " PESOS";

    if (centavos > 0) {
        result += " AND " + convertToWords(centavos) + " CENTAVOS";
    }

    return result.trim();
}

</script>