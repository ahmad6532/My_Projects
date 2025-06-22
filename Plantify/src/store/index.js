import { configureStore } from "@reduxjs/toolkit";
import controls from "./controls";

export const store = configureStore({
  reducer: {
    appControls: controls,
  },
});
