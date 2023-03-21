import { ENTRYPOINT } from "../../config/entrypoint";
import { useCookies } from "@vueuse/integrations/useCookies";
import jwtDecode from "jwt-decode";
const cookies = useCookies();

export async function getAllBids(bids) {
  const requestOptions = {
    method: "GET",
    headers: {
      Authorization: `Bearer ${cookies.get("token")}`,
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  };
  const response = await fetch(ENTRYPOINT + `/bids`, requestOptions);
  //console.log(response.json());
  //const finalRes = await response.json();
  if (response.ok) {
    bids.value = await response.json();
    console.log(bids.value);
  }
}

export async function getBidsByFinished(finishedBool, bids) {
  let uri = `/bids`;
  if (finishedBool === true || finishedBool === "Finished") {
    uri = `/bids?finished=true`;
  } else if (finishedBool === false || finishedBool === "Not Finished") {
    uri = `/bids?finished=false`;
  }
  const requestOptions = {
    method: "GET",
    headers: {
      Authorization: `Bearer ${cookies.get("token")}`,
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  };
  const response = await fetch(ENTRYPOINT + uri, requestOptions);

  if (response.ok) {
    bids.value = await response.json();
  }
}

export async function addNewBid(values) {
  //const formData = new FormData(event.target);
  let decoded = jwtDecode(cookies.get("token"));
  // PUT request using fetch with async/await
  const requestOptions = {
    method: "POST",
    headers: {
      Authorization: `Bearer ${cookies.get("token")}`,
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      creator: `api/users/${decoded.id}`,
      title: values.title,
      startDate: values.startDate,
      endDate: values.endDate,
      startPrice: parseFloat(values.startPrice),
      actualPrice: parseFloat(values.startPrice),
      finished: false,
    }),
  };
  console.log(values.startDate);
  console.log(requestOptions);
  // const response = await fetch(ENTRYPOINT + `/bids`, requestOptions);
  //return response;
}
