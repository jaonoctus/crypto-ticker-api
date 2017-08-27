# Crypto Ticker API

## Output example
```
[
  {
    "name": "POLONIEX",
    "markets": [
      {
        "currency" : "USDTBTC",
        "price": 123.45
      },
      {
        "currency": "USDTDASH",
        "price": 123.45
      }
    ]
  },
  // ...
]
```

## How to use on Google Spreadsheets

### Installation

#### 1. Create a sheet

(https://youtu.be/_ZqdDc-RPjM)

Create a sheet named `Currencies`

#### 2. Create a script

(https://youtu.be/EPQHel1hsVQ)

In menubar, click on `Tools`, then click on `Script editor...`.

**Copy and paste the code below:**

```
function main () {
  const ss = SpreadsheetApp.getActiveSpreadsheet()
  const sheet = ss.getSheetByName('Currencies')

  updateCurrencies(sheet)
}

function updateCurrencies(sheet) {
  const api = 'https://crypto-ticker-api.herokuapp.com'
  const res = UrlFetchApp.fetch(api)
  const data = JSON.parse(res.getContentText())

  sheet.clearContents()
  sheet.appendRow(['Currency', 'Price'])

  for (var i = 0; i < data.length; i++) {
    var exchange = data[i]
    var markets = exchange.markets

    for (var j = 0; j < markets.length; j++) {
      var market = markets[j]

      var name = exchange.name + ':' + market.currency

      sheet.appendRow([name, market.price])
    }
  }
}
```

**Add triggers to update values automatically.** (after save, reload the spreadsheet page)

- Run `main`. Events `From spreadsheet` `On open`

- Run `main`. Events `Time-driven` `Minutes timer` `Every minute`

#### 3. Define a named range

(https://youtu.be/mfSQK9xRcyA)

- Select `Currencies!A2:A1000` and save as `Currencies`

- Select `Currencies!B2:B1000` and save as `Prices`

### Fucking use it

(https://youtu.be/0hIMfiwdGuc)

- Add **Data validation** in the cells you want with **Criteria** `List from a range`: `Currencies`. Set **On validation data** to `Reject input`.

- To get the price, in the same row, set the value to this formula: `=IF(ISBLANK(range); ""; LOOKUP(range; Currencies; Prices))`. "range" is the cell where the currency is.
