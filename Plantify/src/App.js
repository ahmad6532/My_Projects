import React, { useEffect, useState } from "react";
import { Switch, Route, useHistory, useLocation } from "react-router-dom";
import { Link, BrowserRouter as router } from "react-router-dom";
import Button from "@material-ui/core/Button";
import { recognition } from "./API/voicerecognition";
import SliderComponent from "./Slider";
import "./style.css";
import "bootstrap/dist/css/bootstrap.css";
import Spring from "./components/Spring";
import Autumn from "./components/Autumn";
import Summer from "./components/Summer";
import Winter from "./components/Winter";
import { useSpeechSynthesis } from "react-speech-kit";
import ChatBot from "react-simple-chatbot";
import GPT from "./components/ChatGPT";
import { store } from "./store/index";
import { Provider } from "react-redux";
import Input from "./components/input/Input";
import ResultPage from "./pages/ResultPage";
import FetchDetails from "./pages/FetchDetails";
import Gallery from "./components/Gallery";

const App = () => {
  const history = useHistory();
  const [stopReco, setStopReco] = useState(false);

  const [mydata, setMydata] = useState([]);
  const [plantDetails, setplantDetails] = useState([]);
  const [detail, setDetail] = useState([]);
  // const [loading, setloading] = useState(false);
  const [myimages, setMyimages] = useState([]);
  const [diseaseimages, setdiseaseimages] = useState([]);
  const { speak } = useSpeechSynthesis();

  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [comments, setComments] = useState("");
  const [submitted, setSubmitted] = useState(false);

  const handleSubmit2 = (e) => {
    e.preventDefault();
    // Perform any necessary form submission logic here

    // Clear input fields
    setName("");
    setEmail("");
    setPhone("");
    setComments("");

    // Display success message
    setSubmitted(true);
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    var data1 = detail;
    var valLength = detail.length;

    // console.log(c);
    if (detail.length >= 4) {
      const fetchedData = await fetch(
        "http://ahmadfyp.online/api/detail/" + data1
      );
      if (fetchedData.status === 200) {
        const data = await fetchedData.json();

        // [0].plant_id
        console.log(data);
        if (data.length < 1) {
          alert("Data not found!");
          return;
        }
        setMydata(data);
        history.push("/data");
        return;
        // console.log(data[0].plant_id);
      } else {
        alert("Request Failed!");
        return;
        // setloading(false);
      }
    } else {
      alert("Plant Name must be Greater than 3 Characters");
    }
  };
  // change
  useEffect(() => {
    // console.log('again calling',mydata);
    if (mydata.length >= 1) {
      const images = async () => {
        const fetchedDetails = await fetch(
          "http://ahmadfyp.online/api/images/" + mydata[0].plant_id
        );
        if (fetchedDetails.status === 200) {
          const data = await fetchedDetails.json();
          // console.log(data);
          setplantDetails(data);
        }
        // console.log(fetchedData, "details fetched");
      };
      const disease_images = async () => {
        const fetchedDetails = await fetch(
          "http://ahmadfyp.online/api/disease_images/" + mydata[0].plant_id
        );
        if (fetchedDetails.status === 200) {
          const data = await fetchedDetails.json();
          // console.log(data);
          setdiseaseimages(data);
        }
        // console.log(fetchedData, "details fetched");
      };
      images();
      disease_images();
    }
    // console.log('calling');
  }, [mydata]);

  const spring = () => {
    history.push("/spring");
  };
  const winter = () => {
    history.push("/winter");
  };
  const summer = () => {
    history.push("/summer");
  };
  const autumn = () => {
    history.push("/autumn");
  };
  const gallery = () => {
    history.push("/gallery");
  };

  function TextToSpeech() {
    const { speak, cancel, speaking } = useSpeechSynthesis();
    // const [text, setText] = useState('');

    const handleSpeak = () => {
      if (speaking) {
        cancel();
      } else {
        speak({ text: mydata[0].description });
      }
    };

    return (
      <div>
        <button onClick={handleSpeak} class="speakerbtn">
          {speaking ? (
            <img src="assets/f3.png" style={{ height: 25, width: 25 }} />
          ) : (
            <img src="assets/f1.png" style={{ height: 25, width: 25 }} />
          )}
        </button>
      </div>
    );
  }
  function TextToSpeech1() {
    const { speak, cancel, speaking } = useSpeechSynthesis();
    // const [text, setText] = useState('');

    const handleSpeak = () => {
      if (speaking) {
        cancel();
      } else {
        speak({ text: mydata[0].uses });
      }
    };

    return (
      <div>
        <button onClick={handleSpeak} class="speakerbtn">
          {speaking ? (
            <img src="assets/f3.png" style={{ height: 25, width: 25 }} />
          ) : (
            <img src="assets/f1.png" style={{ height: 25, width: 25 }} />
          )}
        </button>
      </div>
    );
  }
  function TextToSpeech2() {
    const { speak, cancel, speaking } = useSpeechSynthesis();
    // const [text, setText] = useState('');

    const handleSpeak = () => {
      if (speaking) {
        cancel();
      } else {
        speak({ text: mydata[0].prevention });
      }
    };

    return (
      <div>
        <button onClick={handleSpeak} class="speakerbtn">
          {speaking ? (
            <img src="assets/f3.png" style={{ height: 25, width: 25 }} />
          ) : (
            <img src="assets/f1.png" style={{ height: 25, width: 25 }} />
          )}
        </button>
      </div>
    );
  }
  function TextToSpeech3() {
    const { speak, cancel, speaking } = useSpeechSynthesis();
    // const [text, setText] = useState('');

    const handleSpeak = () => {
      if (speaking) {
        cancel();
      } else {
        speak({ text: mydata[0].interesting_facts });
      }
    };

    return (
      <div>
        <button onClick={handleSpeak} class="speakerbtn">
          {speaking ? (
            <img src="assets/f3.png" style={{ height: 25, width: 25 }} />
          ) : (
            <img src="assets/f1.png" style={{ height: 25, width: 25 }} />
          )}
        </button>
      </div>
    );
  }

  recognition.onresult = (event) => {
    const command = event.results[0][0].transcript;

    if (command.includes("go to") || command.includes("navigate to")) {
      if (command.includes("home") || command.includes("index")) {
        speak({ text: "Welcome  to the home page of our Plant Identification Web App! Here, you have a variety of options to explore and expand your plant knowledge." });
        history.push("/");
      } else if (
        command.includes("contact") ||
        command.includes("contact us")
      ) {
        speak({ text: "Welcome to our Contact Us page! We're glad you're interested in getting in touch with us" });
        history.push("/contact");
      } else if (command.includes("spring") || command.includes("spring us")) {
        speak({ text: "Welcome to the 'Spring Season Plants' page of our Plant Identification Web App. Here, you can explore a delightful collection of plants that thrive and bloom during the spring season." });
        history.push("/spring");
      } else if (command.includes("summer") || command.includes("summer us")) {
        speak({ text: "Welcome to the 'Summer Season Plants' page of our Plant Identification Web App. Here, you can explore a delightful collection of plants that thrive and bloom during the spring season." });
        history.push("/summer");
      } else if (command.includes("winter") || command.includes("winter us")) {
        speak({ text: "Welcome to the 'Winter Season Plants' page of our Plant Identification Web App. Here, you can explore a delightful collection of plants that thrive and bloom during the spring season." });
        history.push("/winter");
      } else if (command.includes("autumn") || command.includes("autumn us")) {
        speak({ text: "Welcome to the 'Autumn Season Plants' page of our Plant Identification Web App. Here, you can explore a delightful collection of plants that thrive and bloom during the spring season." });
        history.push("/autumn");
      } else if (command.includes("gallery") || command.includes("gallery")) {
        speak({ text: "Here is the Gallery Page." });
        history.push("/gallery");
      } else if (command.includes("about") || command.includes("about us")) {
        speak({ text: " Welcome to the About page of our Plant Identification Web App. Here, you can discover more about the Us and Our app." });
        history.push("/about");
      } else if (command.includes("search") || command.includes("search us")) {
        handleSubmit(event);
        speak({ text: "If You want more detail, you can contect with our Botanical Buddy" });
      }
    } else if (
      command.includes("stop listening") ||
      command.includes("stop recognition") ||
      command.includes("stop recognizing") ||
      command.includes("stop voice controlling") ||
      command.includes("stop voice control")
    ) {
      recognition.stop();
      setStopReco(true);
    }
  };

  recognition.onend = () => {
    if (!stopReco) {
      recognition.start();
    }
  };

  const handleUserInput1 = () => {
    // const a="rose";

    history.push("/chatbot");
    speak({ text: "Hello I am Botanical Buddy. I am here for your help." });
  };

  return (
    <div className="app">
      <div className="col-md-12 py-2" style={{ backgroundColor: "#ffffff" }}>
        {/* {(detail && !loading)? "loading..." : "data  fetched"} */}
        {/* {!loading ? "loading..." : "data  fetched"} */}
        <nav className="navbar navbar-dark">
          <Link className="navbar-brand" to="/">
            <div class="logoh">
              <img
                src="assets/logo2.png"
                style={{
                  height: 54,
                  width: 69,
                  borderRadius: 59,
                  marginRight: 10,
                  marginBottom: 10,
                }}
              />

              <p class="navcont">Plantify</p>
            </div>
          </Link>
          <ul className="nav ml-auto">
            <li class="searchdiv">
              <form onSubmit={handleSubmit}>
                <input
                  class="inputfield"
                  placeholder="Search Plant Here..."
                  type="text"
                  value={detail}
                  onChange={(event) => setDetail(event.target.value)}
                />
                <button class="subbtn" type="submit">
                  Search
                </button>
              </form>
            </li>

            <li className="nav-item"></li>
            <li className="nav-item">
              <Link to="/" className="nav-link text-light">
                <p class="navcont1">Home</p>
              </Link>
            </li>

            <li className="nav-item">
              <Link to="/gallery" className="nav-link text-light">
                <p class="navcont1">Gallery</p>
              </Link>
            </li>
            <li className="nav-item">
              <Link to="/about" className="nav-link text-light">
                <p class="navcont1">About</p>
              </Link>
            </li>
            <li className="nav-item">
              <Link to="/contact" className="nav-link text-light">
                <p class="navcont1">Contact</p>
              </Link>
            </li>
            <li className="nav-item">
              <Link to="/data" className="nav-link text-light"></Link>
            </li>
          </ul>
        </nav>
      </div>
      <Provider store={store}>
        <Switch>
          <Route exact path="/resultPage">
            <ResultPage />
          </Route>
          <Route exact path="/fetchedData">
            <FetchDetails />
          </Route>
          <Route exact path="/">
            <div class="main">
              <div class="over"></div>
              <video class="vdiv" src="assets/videoo.mp4" autoPlay loop muted />
              <div class="overtext">
                <h1 className="text-center uploadtextcolor py-5">
                  Upload Plant Image To Identify It.
                </h1>
                <Input />
                <div style={{ width: 700, marginTop: 40, marginLeft: 350 }}>
                  <text className="text-center uploadtextcolor1 py-5">
                    Please ensure to upload a clear and well-focused image,
                    preferably taken in good lighting conditions. This will help
                    our app provide the most precise and reliable identification
                    results.
                  </text>
                </div>
              </div>

              <text class="stext">
                <h1>Flowers According To Season</h1>
              </text>

              <div class="maincard">
                <div class="sumdiv shadow">
                  <img class="sumimg" src="assets/h1.jpeg" onClick={spring} />
                  <text class="sumtext">Spring</text>
                </div>
                <div class="sumdiv shadow">
                  <img
                    class="sumimg"
                    src="assets/summer2.jpeg"
                    onClick={summer}
                  />
                  <text class="sumtext">Summer</text>
                </div>
                <div class="sumdiv shadow">
                  <img
                    class="sumimg"
                    src="assets/winter2.jpeg"
                    onClick={winter}
                  />
                  <text class="sumtext">Winter</text>
                </div>
                <div class="sumdiv shadow">
                  <img
                    class="sumimg"
                    src="assets/autumn2.jpeg"
                    onClick={autumn}
                  />
                  <text class="sumtext">Autumn</text>
                </div>
              </div>

              <div class="emptydiv">
                <h6 class="h6">⚪⚪⚪</h6>
              </div>
              <div>
                <img class="imgdiv" src="assets/img0.jpg" />
                <div class="overtext1">
                <div>
                  <div class="aboutcontent">
                    <h1 class="aboutheading">ABOUT US</h1>
                    <div class="pdiv">
                      <p class="ptext">
                        At Plantify, we are dedicated to providing a seamless
                        and enriching plant identification experience. Our web
                        app combines cutting-edge technologies, including voice
                        assistant, image identification, random searches, and
                        text-to-speech capabilities, to empower you to explore
                        and understand the world of plants in innovative ways.
                      </p>
                    </div>
                  </div>
                  <div class="aboutcontent">
                    <h4>Who We Are?</h4>
                    <div class="pdiv">
                      <p class="ptext">
                        We are a team of plant enthusiasts, nature lovers, and
                        technology enthusiasts who are passionate about
                        connecting people with the beauty and diversity of the
                        plant kingdom. With our expertise in Web Development and
                        artificial intelligence, we have created a powerful web
                        app that brings together the best of both worlds.
                      </p>
                    </div>
                  </div>
                  <div class="aboutDesc">
                <p class="jeneral">
                  Join us on a journey of botanical discovery with Plantify.
                  Whether you're a beginner, a seasoned gardener, or an avid
                  nature lover, our web app will empower you to explore,
                  identify, and appreciate the magnificent world of plants like
                  never before. Start your plant identification adventure with
                  Plantify today and unlock the wonders of the botanical realm
                  through the power of voice, images, and knowledge!
                </p>
              </div>
                </div>
                  <div class="whitedivimgback">
                    <img class="whitedivimg" src="assets/img0.jpg" />
                  </div>
                </div>
              </div>

              <div class="emptydiv">
                <h6 class="h61"></h6>
              </div>

              <div class="maingallerydiv">
                <div class="imgsection">
                  <img class="galimg" src="assets/gg1.jpeg" />
                  <img class="galimg" src="assets/gg2.jpeg" />
                  <img class="galimg" src="assets/gg3.jpeg" />
                  <img class="galimg" src="assets/gg4.jpeg" />
                  <img class="galimg" src="assets/gg5.jpeg" />
                  <img class="galimg" src="assets/gg6.jpeg" />
                </div>
                <div class="contsection">
                  <div class="contdiv1">
                    <div class="galheadingn">
                      <text class="galheadingtext">Our Gallery</text>
                    </div>
                    <div class="galcontt">
                      <h4>Plants Wallpapers</h4>
                    </div>
                    <div class="galcont">
                      <text>
                        Elevate your screens with our captivating plant
                        wallpapers. Immerse yourself in a tapestry of botanical
                        wonders, from delicate ferns to majestic trees. With
                        stunning high-definition and 4K resolution images, our
                        gallery brings nature to life. Seamlessly integrate our
                        wallpapers with your devices and operating systems for a
                        serene ambiance. Explore and transform your digital
                        aesthetics with the power of nature.
                      </text>
                    </div>
                    <button class="galbtn" onClick={gallery}>
                      <text class="btntext">See All Photos</text>
                    </button>
                  </div>
                </div>
              </div>

              {/* <text className="stext">
              <h1>Useful-Tips & Tricks</h1>
            </text> */}
              {/* <div class="maindivcard" style={{ marginTop: 20 }}>
              <div class="tmain shadow">
                <div class="mtext">
                  <text class="headmulch">How To Mulch Your Garden</text>
                </div>
                <div>
                  <img class="mimg" src="assets/mul.jpeg" />
                </div>
              </div>
              <div class="tmain shadow">
                <div class="mtext">
                  <text class="headmulch">
                    Ways To Store Water In To Your Garden
                  </text>
                </div>
                <div>
                  <img class="mimg" src="assets/j1.jpeg" />
                </div>
              </div>
            </div> */}
            </div>
          </Route>
          <Route exact path="/gallery">
            <Gallery />
          </Route>

          <Route exact path="/chatbot">
            <GPT />
          </Route>
          <Route exact path="/about">
            <div>
              <img class="imgdiv" src="assets/img0.jpg" />
              <div class="overtext22">
                <div>
                  <div class="aboutcontent">
                    <h1 class="aboutheading">ABOUT US</h1>
                    <div class="pdiv">
                      <p class="ptext">
                        At Plantify, we are dedicated to providing a seamless
                        and enriching plant identification experience. Our web
                        app combines cutting-edge technologies, including voice
                        assistant, image identification, random searches, and
                        text-to-speech capabilities, to empower you to explore
                        and understand the world of plants in innovative ways.
                      </p>
                    </div>
                  </div>
                  <div class="aboutcontent">
                    <h4>Who We Are?</h4>
                    <div class="pdiv">
                      <p class="ptext">
                        We are a team of plant enthusiasts, nature lovers, and
                        technology enthusiasts who are passionate about
                        connecting people with the beauty and diversity of the
                        plant kingdom. With our expertise in Web Development and
                        artificial intelligence, we have created a powerful web
                        app that brings together the best of both worlds.
                      </p>
                    </div>
                  </div>
                  <div class="aboutDesc">
                <p class="jeneral">
                  Join us on a journey of botanical discovery with Plantify.
                  Whether you're a beginner, a seasoned gardener, or an avid
                  nature lover, our web app will empower you to explore,
                  identify, and appreciate the magnificent world of plants like
                  never before. Start your plant identification adventure with
                  Plantify today and unlock the wonders of the botanical realm
                  through the power of voice, images, and knowledge!
                </p>
              </div>
                </div>

                <div class="whitedivimgback">
                  <img class="whitedivimg" src="assets/img0.jpg" />
                </div>
              </div>
             
            </div>
          </Route>
          <Route exact path="/spring">
            <Spring />
          </Route>
          <Route exact path="/summer">
            <Summer />
          </Route>
          <Route exact path="/winter">
            <Winter />
          </Route>
          <Route exact path="/autumn">
            <Autumn />
          </Route>

          <Route exact path="/data">
            {mydata.map((item, index) => (
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
            ))}
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
              {plantDetails.map((item, index) => (
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
            {mydata.map((item, index) => (
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
                      <TextToSpeech />
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
            {mydata.map((item, index) => (
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
            {mydata.map((item, index) => (
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
            {mydata.map((item, index) => (
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
            {mydata.map((item, index) => (
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
                      <TextToSpeech1 />
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
              {diseaseimages.map((item, index) => (
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
            {mydata.map((item, index) => (
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
                      <TextToSpeech2 />
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
            {mydata.map((item, index) => (
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
                      <TextToSpeech3 />
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </Route>
          <Route exact path="/contact">
            <div class="main">
              <div class="over"></div>
              <img class="vdiv" src="assets/img000.jpg" />
              <div class="overtext">
                <div class="backdiv">
                  {submitted && (
                    <p class="success">Form submitted successfully!</p>
                  )}
                  <img class="toplogoimg" src="assets/logo1.png" />
                  <form onSubmit={handleSubmit2}>
                    <div className="inputdiv">
                      <span className="lab1">Name:</span>
                      <input
                        type="text"
                        placeholder="Name Here..."
                        className="inputfield1"
                        value={name}
                        onChange={(e) => setName(e.target.value)}
                      />
                      <span className="lab1">Email:</span>
                      <input
                        type="text"
                        placeholder="Enter Email..."
                        className="inputfield1"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                      />
                      <span className="lab11">Phone Number:</span>
                      <input
                        type="text"
                        placeholder="Phone Number..."
                        className="inputfield1"
                        value={phone}
                        onChange={(e) => setPhone(e.target.value)}
                      />
                      <span className="lab111">Comments:</span>
                      <textarea
                        placeholder="Comments..."
                        className="inputfield2"
                        value={comments}
                        onChange={(e) => setComments(e.target.value)}
                      />
                    </div>
                    <button type="submit" className="subbtn1">
                      <span className="subbtntext">Submit</span>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </Route>
        </Switch>
      </Provider>

      {/* <footer class="page-footer font-small blue">

  
  <div class="footer-copyright text-center py-3">© 2023 Copyright: FY Project
    <a href="/"> www.fyp.com</a>
  </div>
  

</footer> */}
    </div>
  );
};

const styles = {
  imgdis: {
    height: 190,
    width: 200,
    flexDirection: "row",
    borderRadius: 17,
  },
};

export default App;
