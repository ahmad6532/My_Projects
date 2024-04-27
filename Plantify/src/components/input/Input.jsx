
import { Button } from '@material-ui/core';
import React, { useState } from 'react'
import { useHistory } from 'react-router-dom/cjs/react-router-dom.min';
import { useDispatch, useSelector } from 'react-redux';
import { storingFile } from '../../store/controls'

const Input = () => {
  const history = useHistory()
  const dispatch=useDispatch()
  const [imageRef, setimageRef] = useState("");
  // handling image
  const handleImage = (e) => {
    const file = e.target.files[0];
    // console.log(file);
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onloadend = () => {
      setimageRef(reader.result);
      dispatch(storingFile({ newVal: reader.result }));
      history.push('/resultPage')
      return;
      // setsaveButton(true);
    };
  };

  return (
    <div
      style={{
        display: "flex",
        margin: "auto",
        width: 400,
        flexWrap: "wrap",
      }}
      class="uploaddiv"
    >
      <input
        class="uploadinput"
        type="file"
        accept="image/jpeg,image/jpg,image/png"
        style={{ display: "none" }}
        id="contained-button-file"
        onChange={handleImage}
      />

      <label htmlFor="contained-button-file">
        <Button
          class="uploadbtn"
          variant="contained"
          color="primary"
          component="span"
        >
          <p class="utext">Upload</p>
          <img class="uimg" src="assets/up.png" />
        </Button>
      </label>
    </div>
  )
}

export default Input
