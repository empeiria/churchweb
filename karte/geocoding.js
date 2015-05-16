/* This code is used in the Google Docs Spreadsheet to calculate the geographic coordinates automatically. */

/**
 * Update the geocode data for one row.
 */
function geocodeEditedRow(cellstring) {
  var sheet = SpreadsheetApp.getActiveSheet();
  var cells = sheet.getRange(cellstring); // sheet.getDataRange();

  var streetColumn = 6; // column F (street)
  var addressColumn = 16; // column P (address concenated)
  var addressRow;

  var latColumn = addressColumn + 1; // column Q
  var lngColumn = addressColumn + 2; // column R

  var geocoder = Maps.newGeocoder().setRegion('de');
  var street, postalCode, town, location;

  // currently this loop will only run for one row because cells contains only one
  for (addressRow = 1; addressRow <= cells.getNumRows(); ++addressRow) {
    street = cells.getCell(addressRow, streetColumn).getValue();
    postalCode = cells.getCell(addressRow, streetColumn+1).getValue();
    town = cells.getCell(addressRow, streetColumn+2).getValue();
    address = street + ", " + postalCode + " " + town;
    cells.getCell(addressRow, addressColumn).setValue(address);

    // Geocode the address and plug the lat, lng pair into the 
    // 2nd and 3rd elements of the current range row.
    location = geocoder.geocode(address);
    Logger.log(location);
    // Only change cells if geocoder seems to have gotten a 
    // valid response.
    if (location.status == 'OK') {
      lat = location["results"][0]["geometry"]["location"]["lat"];
      lng = location["results"][0]["geometry"]["location"]["lng"];

      cells.getCell(addressRow, latColumn).setValue(lat);
      cells.getCell(addressRow, lngColumn).setValue(lng);
    }
  }
};

/**
 * Update the geocode data for the whole document.
 */
function geocodeUpdate() {
  var sheet = SpreadsheetApp.getActiveSheet();
  var cells = sheet.getDataRange();

  var streetColumn = 6; // column F (street)
  var addressColumn = 16; // column P (address concenated)
  var addressRow;

  var latColumn = addressColumn + 1; // column Q
  var lngColumn = addressColumn + 2; // column R

  var geocoder = Maps.newGeocoder().setRegion('de');
  var street, postalCode, town, location;

  // first row is the table header
  for (addressRow = 2; addressRow <= cells.getNumRows(); ++addressRow) {
    street = cells.getCell(addressRow, streetColumn).getValue();
    postalCode = cells.getCell(addressRow, streetColumn+1).getValue();
    town = cells.getCell(addressRow, streetColumn+2).getValue();
    address = street + ", " + postalCode + " " + town;
    cells.getCell(addressRow, addressColumn).setValue(address);

    // Geocode the address and plug the lat, lng pair into the 
    // 2nd and 3rd elements of the current range row.
    location = geocoder.geocode(address);
    Logger.log(location);
    // Only change cells if geocoder seems to have gotten a 
    // valid response.
    if (location.status == 'OK') {
      lat = location["results"][0]["geometry"]["location"]["lat"];
      lng = location["results"][0]["geometry"]["location"]["lng"];

      cells.getCell(addressRow, latColumn).setValue(lat);
      cells.getCell(addressRow, lngColumn).setValue(lng);
    }
  }
};

/**
 * This function is called after editing one cell in the table. 
 */
function onEdit(e){
  var row = e.range.getRow();
  if (row != 1) {
    var cellstring = "A" + row + ":" + "R" + row; // columns A to R
    geocodeEditedRow(cellstring);
  }
};

/**
 * This function is called during opening the document.
 */
function onOpen() {
  geocodeUpdate();
}