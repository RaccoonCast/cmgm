async function downloadLetter(letterId, extStatusCode, evt) {
  // Display to the user that their file is downloading
  evt.target.style.color = "lightgray";
  evt.target.style.fontStyle = "italic";
  evt.target.style.cursor = "wait";

  try {
    const res = await fetch("proxy/cors_proxy.php?csurl=https://oeaaa.faa.gov/oeaaa/oe3a/external/portal-api/caseFiling/letterDataByLetterId.do", {
      method: "POST",
      headers: {
        accept: "application/json, text/plain, */*",
        "accept-language": "en-US,en;q=0.9",
        "cache-control": "no-cache",
        "content-type": "application/json;charset=UTF-8",
      },
      body: JSON.stringify({
        letterId: letterId,
        extStatusCode: extStatusCode || "EXT",
      }),
    });

    const data = await res.arrayBuffer();

    const blob = new Blob([data], { type: "application/pdf" });

    const blobUrl = URL.createObjectURL(blob);

    window.open(blobUrl, "_blank");
  } catch (err) {
    console.error("An error occured while downloading letter:", err);
  }

  // End "downloading" indication
  evt.target.style.color = "purple"; // set to purple for visited
  evt.target.style.fontStyle = "";
  evt.target.style.cursor = "";
}
