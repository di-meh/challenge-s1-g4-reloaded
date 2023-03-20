import { ENTRYPOINT } from "../../config/entrypoint";
import { useCookies } from "@vueuse/integrations/useCookies";
import jwtDecode from "jwt-decode";
const cookies = useCookies();

export async function getAllBids(bids) {
  const requestOptions = {
    method: "GET",
    headers: { "Content-Type": "application/json" },
  };
  const response = await fetch(ENTRYPOINT + `/bids`, requestOptions);
  const finalRes = await response.json();
  bids.value = finalRes["hydra:member"];
  console.log(bids.value);
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
  const response = await fetch(ENTRYPOINT + `/bids`, requestOptions);
  return response;
}
