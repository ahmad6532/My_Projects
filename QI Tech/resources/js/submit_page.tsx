import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './components/Main';
import DesignerContextProvider from './components/context/DesignerContext';
import { createTheme,ThemeProvider } from '@mui/material';
import FormSubmissionPage from './components/FormSubmissionPage';
import {toast, ToastContainer} from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { FormElementInstance } from './components/FormElements';
let theme = createTheme({
    palette: {
      primary: {
        main: '#72c4ba',
      },
      secondary: {
        main: '#edf2ff',
      },
    },
    typography: {
        fontFamily: 'Littera Text, Arial, sans-serif', // Specify a fallback font
      },
  })

  const json = [
    {
      "id": "4434",
      "type": "TextField",
      "extraAttributes": {
        "label": "Text Fieldasd",
        "helperText": "Helper text",
        "placeholder": "Typasdf",
        "required": true
      }
    }
  ]

ReactDOM.createRoot(document.getElementById('react-root') as HTMLElement).render(
    <React.StrictMode>
        <DesignerContextProvider>
            <ThemeProvider theme={theme}>
                <FormSubmissionPage content={json as FormElementInstance[]}/>
                <ToastContainer/>
            </ThemeProvider>
        </DesignerContextProvider>
    </React.StrictMode>
);
