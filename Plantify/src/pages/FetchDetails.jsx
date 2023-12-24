import React, { useState, useEffect } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import {useHistory} from "react-router-dom";
import { resultQueryStore } from '../store/controls'
import axios from 'axios'

const FetchDetails = () => {
    const { fetchedDetailsQuery } = useSelector((state) => state.appControls)
    const [SearchedDetails, setSearchedDetails] = useState([])
    const [searchedImages, setsearchedImages] = useState([])
    const [diseaseImages, setdiseaseImages] = useState([])
    const history = useHistory();
    // console.log(SearchedDetails);
    useEffect(() => {
        //   searching data
        const searchedData = async () => {
            try {
                const searchedData = await axios.get(`http://ahmadfyp.online/api/detail/${fetchedDetailsQuery}`)
                if (searchedData.status === 200) {
                    setSearchedDetails(searchedData.data)
                   
                }
            } catch (error) {
                console.log(error.message);
                return;
            }
        }
        // searching images
        const searchedMyImages = async () => {
            try {
                // const a=SearchedDetails[0].plant_id;
                // console.log(a);
                const searchedImg = await axios.get("http://ahmadfyp.online/api/images/" + SearchedDetails[0].plant_id)
                if (searchedImg.status === 200) {
                    // console.log(searchedImg.data);
                    setsearchedImages(searchedImg.data)
                }
            } catch (error) {
                console.log(error.message);
                return;
            }
        }
        const searchedMyDisease = async () => {
            try {
                // const a=SearchedDetails[0].plant_id;
                // console.log(a);
                const searchedImg = await axios.get("http://ahmadfyp.online/api/disease_images/" + SearchedDetails[0].plant_id)
                if (searchedImg.status === 200) {
                    // console.log(searchedImg.data);
                    setdiseaseImages(searchedImg.data)
                }
            } catch (error) {
                console.log(error.message);
                return;
            }
        }
        searchedData();
        searchedMyImages();
        searchedMyDisease();
    }, [SearchedDetails])


    const handleUserInput1 = () => {
     
        history.push("/chatbot");
    };

    return (
        <div>
        {SearchedDetails.length >= 1 ?
        <div>

           
          
                {SearchedDetails.map((item, index) => {
                    return (
                        <div
                            key={index}
                            style={{
                                paddingLeft: 130,
                                paddingRight: 130,
                                paddingTop: 30,
                                flexDirection: "column",
                            }}
                            class="d-flex justify-content-center "
                        >
                            <div class="shadow p-3 mb-5 bg-dark rounded d-flex justify-content-center Fiddle Leaf Fig">
                                <h1 class="heading">{item.plant_name}</h1>
                                <div class="chatimg shadow">
                                    <img
                                        src="assets/bot3.png"
                                        class="chatimg1"
                                        onClick={handleUserInput1}
                                    />
                                </div>
                            </div>
                            <div>
                                <div
                                    class="flexDisplay shadow p-2 mb-2 rounded"
                                    style={{ flexDirection: "row" }}
                                >
                                    <div style={{ width: 300 }}>
                                        <p style={{ fontWeight: 700 }}>Also Known:</p>
                                    </div>

                                    <div style={{ width: 700 }}>
                                        <p>{item.known_as}</p>
                                    </div>
                                </div>
                                <div
                                    class="flexDisplay shadow p-2 mb-2 rounded"
                                    style={{ flexDirection: "row" }}
                                >
                                    <div style={{ width: 300 }}>
                                        <p style={{ fontWeight: 700 }}>Scientific Name:</p>
                                    </div>

                                    <div style={{ width: 700 }}>
                                        <p>{item.scientific_name}</p>
                                    </div>
                                </div>
                            </div>






                        </div>
                    )
                })}



                
               
             <div
              style={{
                paddingLeft: 130,
                paddingRight: 130,
                paddingTop: 7,
              }}
              class="field"
            >
              <img class="description-img" src="assets/field2.png" />
              <h3 class="fheading">Field Guide</h3>
            </div>

            <div
              style={{
                paddingLeft: 130,
                paddingRight: 130,
                paddingTop: 7,
                flexDirection: "row",
              }}
              class="plant-images d-flex justify-content-center"
            >
              {searchedImages.map((item, index) => (
                <div key={index}>
                  <img class="shadow imghover" src={item.plant_images} />
                </div>
              ))}
            </div>


            <div
              style={{
                paddingLeft: 130,
                paddingRight: 130,
                paddingTop: 7,
                flexDirection: "row",
              }}
              class="plant-images d-flex justify-content-center"
            >
              {SearchedDetails.map((item, index) => (
                <div key={index}>
                  <img class="shadow imghover" src={item.plant_images} />
                </div>
              ))}
            </div>

            <div>
              <div
                style={{
                  paddingLeft: 130,
                  paddingRight: 130,
                  paddingTop: 7,
                }}
                class="field"
              >
                <img class="description-img" src="assets/descrip_image.png" />
                <h3 class="fheading">Description</h3>
              </div>
            </div>
            {SearchedDetails.map((item, index) => (
              <div
                key={index}
                style={{
                  paddingLeft: 130,
                  paddingRight: 130,
                  paddingTop: 7,
                  flexDirection: "column",
                }}
                class="d-flex justify-content-center"
              >
                <div>
                  <div
                    class="flexDisplay shadow p-2 mb-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div class="justi1" style={{ width: 1100 }}>
                      <p>{item.description}</p>
                    </div>
                  </div>
                </div>
              </div>
            ))}

            <div
              style={{
                paddingLeft: 130,
                paddingRight: 130,
                paddingTop: 7,
              }}
              class="field"
            >
              <img class="description-img1" src="assets/char6.png" />
              <h3 class="fheading">Characteristics</h3>
            </div>
            {SearchedDetails.map((item, index) => (
              <div
                key={index}
                style={{
                  paddingLeft: 130,
                  paddingRight: 130,
                  paddingTop: 7,
                  flexDirection: "column",
                }}
                class="d-flex justify-content-center"
              >
                <div>
                  <div
                    class="flexDisplay1 p-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div style={{ width: 400 }}>
                      <p class="graycolor" style={{ fontWeight: 600 }}>
                        Color
                      </p>
                    </div>

                    <div style={{ width: 700 }}>
                      <p>{item.color}</p>
                    </div>
                  </div>
                  <div
                    class="flexDisplay1 p-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div style={{ width: 400 }}>
                      <p class="graycolor" style={{ fontWeight: 600 }}>
                        Plant Type
                      </p>
                    </div>

                    <div style={{ width: 700 }}>
                      <p>{item.plant_type}</p>
                    </div>
                  </div>
                  <div
                    class="flexDisplay1 p-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div style={{ width: 400 }}>
                      <p class="graycolor" style={{ fontWeight: 600 }}>
                        Leaf Color
                      </p>
                    </div>

                    <div style={{ width: 700 }}>
                      <p>{item.leaf_color}</p>
                    </div>
                  </div>
                  <div
                    class="flexDisplay1 p-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div style={{ width: 400 }}>
                      <p class="graycolor" style={{ fontWeight: 600 }}>
                        Life Span
                      </p>
                    </div>

                    <div style={{ width: 700 }}>
                      <p>{item.life_span}</p>
                    </div>
                  </div>
                  <div
                    class="flexDisplay1 p-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div style={{ width: 400 }}>
                      <p class="graycolor" style={{ fontWeight: 600 }}>
                        Bloom Time
                      </p>
                    </div>

                    <div style={{ width: 700 }}>
                      <p>{item.bloom_time}</p>
                    </div>
                  </div>
                  <div
                    class="flexDisplay1 p-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div style={{ width: 400 }}>
                      <p class="graycolor" style={{ fontWeight: 600 }}>
                        Plant Size
                      </p>
                    </div>

                    <div style={{ width: 700 }}>
                      <p>{item.plant_size}</p>
                    </div>
                  </div>
                  <div
                    class="flexDisplay1 p-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div style={{ width: 400 }}>
                      <p class="graycolor" style={{ fontWeight: 600 }}>
                        Fruit
                      </p>
                    </div>

                    <div style={{ width: 700 }}>
                      <p>{item.fruit}</p>
                    </div>
                  </div>
                  <div
                    class="flexDisplay1 p-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div style={{ width: 400 }}>
                      <p class="graycolor" style={{ fontWeight: 600 }}>
                        Habitat
                      </p>
                    </div>

                    <div style={{ width: 700 }}>
                      <p>{item.habitate}</p>
                    </div>
                  </div>
                </div>
              </div>
            ))}
<div
              style={{
                paddingLeft: 130,
                paddingRight: 130,
                paddingTop: 7,
              }}
              class="field"
            >
              <img
                class="description-img1 imgheight"
                src="assets/condition.png"
              />
              <h3 class="fheading">Conditions Requirement</h3>
            </div>
            {SearchedDetails.map((item, index) => (
              <div
                key={index}
                style={{
                  paddingLeft: 130,
                  paddingRight: 130,
                  paddingTop: 7,
                  flexDirection: "column",
                }}
                class="d-flex justify-content-center"
              >
                <div>
                  <div
                    class="flexDisplay1 p-2 rounded divcondition"
                    style={{ flexDirection: "row" }}
                  >
                    <div class="divwidth1">
                      <img
                        class="description-img crimg"
                        src="assets/sun1.png"
                      />
                    </div>

                    <div class="conditions divwidth2">
                      <div>
                        <p class="tbold">Sunlight</p>
                      </div>
                      <div>
                        <p>{item.sunlight}</p>
                      </div>
                    </div>
                  </div>
                  <div
                    class="flexDisplay1 p-2 rounded divcondition"
                    style={{ flexDirection: "row" }}
                  >
                    <div class="divwidth1">
                      <img
                        class="description-img crimg"
                        src="assets/soil.png"
                      />
                    </div>

                    <div class="conditions divwidth2">
                      <div>
                        <p class="tbold">Soil</p>
                      </div>
                      <div>
                        <p>{item.soil}</p>
                      </div>
                    </div>
                  </div>
                </div>

                <div
                  class="flexDisplay1 p-2 rounded divcondition"
                  style={{ flexDirection: "row" }}
                >
                  <div class="divwidth1">
                    <img class="description-img crimg1" src="assets/zoon.png" />
                  </div>

                  <div class="conditions divwidth2">
                    <div>
                      <p class="tbold">Hardiness Zone</p>
                    </div>
                    <div>
                      <p>{item.hardiness_zone}</p>
                    </div>
                  </div>
                </div>
                <div
                  class="flexDisplay1 p-2 rounded divcondition"
                  style={{ flexDirection: "row" }}
                >
                  <div class="divwidth1">
                    <img class="description-img crimg" src="assets/temp.png" />
                  </div>

                  <div class="conditions divwidth2">
                    <div>
                      <p class="tbold">Temperature</p>
                    </div>
                    <div>
                      <p>{item.temperature}</p>
                    </div>
                  </div>
                </div>
                <div
                  class="flexDisplay1 p-2 rounded divcondition"
                  style={{ flexDirection: "row" }}
                >
                  <div class="divwidth1">
                    <img class="description-img crimg" src="assets/water.png" />
                  </div>

                  <div class="conditions divwidth2">
                    <div>
                      <p class="tbold">Water</p>
                    </div>
                    <div>
                      <p>{item.water}</p>
                    </div>
                  </div>
                </div>
                <div
                  class="flexDisplay1 p-2 rounded divcondition"
                  style={{ flexDirection: "row" }}
                >
                  <div class="divwidth1">
                    <img class="description-img crimg" src="assets/fert.png" />
                  </div>

                  <div class="conditions divwidth2">
                    <div>
                      <p class="tbold">Fertilizer</p>
                    </div>
                    <div>
                      <p>{item.fertilization}</p>
                    </div>
                  </div>
                </div>
                <div
                  class="flexDisplay1 p-2 rounded divcondition"
                  style={{ flexDirection: "row" }}
                >
                  <div class="divwidth1">
                    <img
                      class="description-img crimg"
                      src="assets/planting.png"
                    />
                  </div>

                  <div class="conditions divwidth2">
                    <div>
                      <p class="tbold">Planting Time</p>
                    </div>
                    <div>
                      <p>{item.planting_time}</p>
                    </div>
                  </div>
                </div>
                <div
                  class="flexDisplay1 p-2 rounded divcondition"
                  style={{ flexDirection: "row" }}
                >
                  <div class="divwidth1">
                    <img class="description-img crimg" src="assets/har.png" />
                  </div>

                  <div class="conditions divwidth2">
                    <div>
                      <p class="tbold">Harvest Time</p>
                    </div>
                    <div>
                      <p>{item.harvest_time}</p>
                    </div>
                  </div>
                </div>
                <div
                  class="flexDisplay1 p-2 rounded divcondition"
                  style={{ flexDirection: "row" }}
                >
                  <div class="divwidth1">
                    <img class="description-img crimg" src="assets/hum.png" />
                  </div>

                  <div class="conditions divwidth2">
                    <div>
                      <p class="tbold">Humidity</p>
                    </div>
                    <div>
                      <p>{item.humidity}</p>
                    </div>
                  </div>
                </div>
              </div>
            ))}

            <div
              style={{
                paddingLeft: 130,
                paddingRight: 130,
                paddingTop: 7,
              }}
              class="field"
            >
              <img class="description-img1" src="assets/sci.png" />
              <h3 class="fheading">Scientific Classification</h3>
            </div>
            {SearchedDetails.map((item, index) => (
              <div
                key={index}
                style={{
                  paddingLeft: 130,
                  paddingRight: 130,
                  paddingTop: 7,
                  flexDirection: "column",
                }}
                class="d-flex justify-content-center"
              >
                <div>
                  <div
                    class="flexDisplay1 p-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div style={{ width: 400 }}>
                      <p class="graycolor" style={{ fontWeight: 600 }}>
                        Kindom
                      </p>
                    </div>

                    <div style={{ width: 700 }}>
                      <p>{item.kindom}</p>
                    </div>
                  </div>
                  <div
                    class="flexDisplay1 p-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div style={{ width: 400 }}>
                      <p class="graycolor" style={{ fontWeight: 600 }}>
                        Order
                      </p>
                    </div>

                    <div style={{ width: 700 }}>
                      <p>{item.plant_order}</p>
                    </div>
                  </div>
                  <div
                    class="flexDisplay1 p-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div style={{ width: 400 }}>
                      <p class="graycolor" style={{ fontWeight: 600 }}>
                        Family
                      </p>
                    </div>

                    <div style={{ width: 700 }}>
                      <p>{item.family}</p>
                    </div>
                  </div>
                  <div
                    class="flexDisplay1 p-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div style={{ width: 400 }}>
                      <p class="graycolor" style={{ fontWeight: 600 }}>
                        Genus
                      </p>
                    </div>

                    <div style={{ width: 700 }}>
                      <p>{item.genus}</p>
                    </div>
                  </div>
                  <div
                    class="flexDisplay1 p-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div style={{ width: 400 }}>
                      <p class="graycolor" style={{ fontWeight: 600 }}>
                        Species
                      </p>
                    </div>

                    <div style={{ width: 700 }}>
                      <p>{item.species}</p>
                    </div>
                  </div>
                </div>
              </div>
            ))}

            <div>
              <div
                style={{
                  paddingLeft: 130,
                  paddingRight: 130,
                  paddingTop: 7,
                }}
                class="field"
              >
                <img class="description-img2" src="assets/splash1.png" />
                <h3 class="fheading">Uses</h3>
              </div>
            </div>
            {SearchedDetails.map((item, index) => (
              <div
                key={index}
                style={{
                  paddingLeft: 130,
                  paddingRight: 130,
                  paddingTop: 7,
                  flexDirection: "column",
                }}
                class="d-flex justify-content-center"
              >
                <div>
                  <div
                    class="flexDisplay shadow p-2 mb-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div class="justi" style={{ width: 1100 }}>
                      <p>{item.uses}</p>
                    </div>
                  </div>
                </div>
              </div>
            ))}
           <div
              style={{
                paddingLeft: 130,
                paddingRight: 130,
                paddingTop: 7,
              }}
              class="field"
            >
              <img class="description-img2" src="assets/dis.png" />
              <h3 class="fheading">Common Diseases</h3>
            </div>

            <div
              style={{
                paddingLeft: 130,
                paddingRight: 130,
                paddingTop: 7,
                flexDirection: "row",
              }}
              class="plant-images d-flex justify-content-center"
            >
              {diseaseImages.map((item, index) => (
                <div key={index}>
                  <img class="shadow imghover" src={item.images} />
                  <p class="disease_text">{item.disease_name}</p>
                </div>
              ))}
            </div>






            <div>
              <div
                style={{
                  paddingLeft: 130,
                  paddingRight: 130,
                  paddingTop: 7,
                }}
                class="field"
              >
                <img class="description-img" src="assets/danp.png" />
                <h3 class="fheading">Precautions</h3>
              </div>
            </div>
            {SearchedDetails.map((item, index) => (
              <div
                key={index}
                style={{
                  paddingLeft: 130,
                  paddingRight: 130,
                  paddingTop: 7,
                  flexDirection: "column",
                }}
                class="d-flex justify-content-center"
              >
                <div>
                  <div
                    class="flexDisplay shadow p-2 mb-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div class="linebreak">
                      <p class="justi">{item.prevention}</p>
                    </div>
                  </div>
                </div>
              </div>
            ))}

            <div>
              <div
                style={{
                  paddingLeft: 130,
                  paddingRight: 130,
                  paddingTop: 7,
                }}
                class="field"
              >
                <img class="description-img" src="assets/greensmile.png" />
                <h3 class="fheading">Interesting Facts</h3>
              </div>
            </div>
            {SearchedDetails.map((item, index) => (
              <div
                key={index}
                style={{
                  paddingLeft: 130,
                  paddingRight: 130,
                  paddingTop: 7,
                  flexDirection: "column",
                }}
                class="d-flex justify-content-center"
              >
                <div>
                  <div
                    class="flexDisplay shadow p-2 mb-2 rounded"
                    style={{ flexDirection: "row" }}
                  >
                    <div class="justi" style={{ width: 1100 }}>
                      <p>{item.interesting_facts}</p>
                    </div>
                  </div>
                </div>
              </div>
            ))}





        </div>
         : 'Loading...'
        }
        </div>
    )
}

export default FetchDetails
