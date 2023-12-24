import { createSlice } from "@reduxjs/toolkit";

const initialState = {
  image: "",
  fetchedDetailsQuery: "",
};

export const controlsSlice = createSlice({
  name: "controls",
  initialState,
  reducers: {
    storingFile: (state, actions) => {
      const { newVal } = actions.payload;
      state.image = newVal;
    },
    resultQueryStore: (state, actions) => {
      const { newVal } = actions.payload;
      state.fetchedDetailsQuery = newVal;
    },
  },
});

// Action creators are generated for each case reducer function
export const { storingFile, resultQueryStore } = controlsSlice.actions;

export default controlsSlice.reducer;
