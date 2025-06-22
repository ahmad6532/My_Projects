import React, { useState, useEffect } from 'react'
import { useHistory } from 'react-router-dom/cjs/react-router-dom.min';
import "../style.css";
import "bootstrap/dist/css/bootstrap.css";
import { useSelector, useDispatch } from 'react-redux';
import { storingFile } from '../store/controls';
import axios from 'axios';
import { resultQueryStore } from '../store/controls';
// import * as tf from '@tensorflow/tfjs';
// import * as tmImage from '@teachablemachine/image';
// import '@teachablemachine/image/dist/teachablemachine-image.min.css';
// import MetaData from '../modalfiles/metadata.json'
// import ModalData from '../modalfiles/model.json'

const ResultPage = () => {
  const dispatch = useDispatch()
  const history = useHistory()
  const { image } = useSelector((state) => state.appControls)
  const [predictionResponse, setpredictionResponse] = useState([])
  const [imageDetails, setimageDetails] = useState('')
  const [imageURL, setimageURL] = useState('')
  const [loading, setloading] = useState(true)

  const [imageSelected, setImageSelected] = useState(null);

  // console.log(imageURL);
  // handling image
  const handleImage = (e) => {
    const file = e.target.files[0];
    // console.log(file);
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onloadend = () => {
      setImageSelected(reader.result);
      dispatch(storingFile({ newVal: reader.result }));
      return;
    };
  };

  // dispatch(storingFile({ newVal: reader.result }));
  // uploadimage
  const uploadingImage = async () => {
    if (!image) {
      return alert("You have not Selected the Image.K indly Select the Image First!")
    }
    // setpredictionResponse([]);
    // setimageDetails('')
    setloading(true)
    try {
      const data = {
        base64Image: image
      };
      const response = await axios.post('http://localhost:5005/api/image/classify', data);
      if (response.status === 200) {
        setpredictionResponse(response.data.prediections)
        setimageDetails(response.data.uploadedImage)
        setloading(false)
        // console.log(response.data.predictions[0]?.class);
        // dispatch(resultQueryStore({ newVal: response.data ? response.data.predictions[0]?.class.toLocaleLowerCase() : '' }))
        return;
      }
      // console.log('Response:', response.data);
    } catch (error) {
      setloading(false)
      console.log(error.message);
      alert('An error occured!')
      return
    }
  }


  const MoreDetails = () => {
    // console.log('details');
    history.push('/fetchedData')
    console.log(predictionResponse[0]?.class);
    dispatch(resultQueryStore({ newVal: predictionResponse[0]?.class.toLocaleLowerCase() }))
    return;
  }

  useEffect(() => {
    // console.log('side effect running...');
    uploadingImage()
  }, []);


  return (
    <div className='window'>
        {/* inputs */}
        <h3>You can Identify One Image at a time.</h3>
        <div className='resultpageWindow'>
          {/* left side */}
          <div className='leftside'>
            <input type="file" accept='image/jpg, image/jpeg, image/png' className='imagePick' onChange={handleImage} />
            <p class="or">OR</p>
            <input type="text" placeholder='Paste the Image Url here.' className='imageUrl' onChange={(e) => setimageURL(e.target.value)} />
            {/* <br /> */}
            <button disabled={loading} className='btnupload' onClick={() => uploadingImage()}>Upload </button>
            {/* <p>Requested Route: /image/classify</p> */}
            {/* showing image */}
            {image && <div>
              <div><text class="selectedtext">
              Your Selected Image
                </text></div>
              <img src={image} alt="not found" className='previewImagedesign' />
            </div>}
          </div>
          {/* right side */}
          <div className='rightside'>
      
            <div className='imageresults'>
              <h4>Prediction:</h4>
              <p className='percentage'>{!loading ? `${predictionResponse[0]?.class} ${predictionResponse[0]?.score.toFixed(2)}` : 'Loading...'}%</p>
            </div>
            <div className='resultImagediv'>
              {!loading ? <img src={imageDetails} alt="Image Not Found" className='resultImagedesign' /> : 'Loading...'}

            </div>
            
            <div className='resultCompare'>
              <div >
                {/* <p>View Details :</p> */}
                <button class="galbtn1"onClick={MoreDetails}>
                  <text class="detailbtntext">
                  View Details
                  </text>
                </button>
                {/* <p className='resultCompareClass'>
                  {!loading ? `${predictionResponse[0]?.class} ${predictionResponse[0]?.score.toFixed(2)}%, ${predictionResponse[1]?.class} ${predictionResponse[1]?.score.toFixed(2)}%` : 'loading...'}
                </p> */}
              </div>
            </div>
          </div>
        </div>
        <div>
        </div>
      </div>
  )
}

export default ResultPage
