import React, { useEffect, useState } from "react";
import Designer from "./Designer";
import DesignerSidebar from "./DesignerSidebar";
import { DndContext, MouseSensor, TouchSensor, useSensor, useSensors } from "@dnd-kit/core";
import DragOverlayWrapper from "./DragOverlayWrapper";
import PropertiseSidebar from "./PropertiseSidebar";
import useDesigner from "./hooks/useDesigner";
import PreviewDesigner from "./PreviewDesigner";
import SplitButton from "./SplitButton";
import axios from 'axios';
import { CircularProgress, Fade, LinearProgress, Stack } from "@mui/material";
import { toast } from "react-toastify";
import { FormPage } from "./FormElements";
const App: React.FC = () => {
    const mouseSensor = useSensor(MouseSensor,{
        activationConstraint:{
            distance:10
        }
    });
    const touchSensor = useSensor(TouchSensor,{
        activationConstraint:{
            delay:300,
            tolerance:5
        }
    });
    const sensors = useSensors(mouseSensor,touchSensor);
    const {setOpenPreview,elements,setElements} = useDesigner();
    const serverAddress = `${window.location.protocol}//${window.location.hostname}:${window.location.port}`;
    const path = window.location.pathname;
    const segments = path.split('/');
    const id = segments[segments.length - 1];
    const url = `${serverAddress}/head_office/bespokeforms/form_template/${id}`;

    const [data, setData] = useState<{ data: string, form_name: string } | null>(null);
    const [loading, setLoading] = useState(true);
    const [loadingSave, setLoadingSave] = useState(false);
    const [error, setError] = useState<any>(null);

    useEffect(() => {
        const fetchData = async () => {
        try {
            setLoading(true);
            const response = await axios.get<{ data: string, form_name: string }>(`${serverAddress}/head_office/bespokeforms/get_form_json/${id}`);
            setData(response.data);
            toast.success('Form loaded successfully!');
        } catch (error) {
            setError(error);
        } finally {
            setLoading(false);
        }
        };
        fetchData();
    }, []); 
    
    useEffect(() => {
        if(data){
            const parsedData: FormPage[] = JSON.parse(data.data);
            setElements(parsedData);
            document.title = data.form_name;
        }
    }, [data]);

        const handlePostRequest = async (url:string) => {
            try {
            setLoadingSave(true);
            const data = JSON.stringify(elements);
            const postData = { data };
            const response = await axios.post(url, postData);
            if(response.status === 200){
                toast.success('Form Saved successfully!');
            }
            } catch (error) {
            setError(error);
            } finally {
            setLoadingSave(false);
            }
        };

    if (loading) return (
        <Stack width='100%' height='100vh' justifyContent='center' alignItems='center' sx={{background:'rgba(0,0,0,0.4)'}}>
            <LinearProgress sx={{width:'20%',height:'8px',borderRadius:'5px'}} />            
        </Stack>
    );

    return (
        <div style={{overflow:'hidden',height:'100vh'}}>
            <nav className="form-nav">
                <a href={url} className="outline-btn">
                    <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M20 12H4M4 12L10 18M4 12L10 6"
                            stroke="black"
                            strokeWidth="2"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                        />
                    </svg>
                </a>
                <div>
                    <h4 className="m-0">{data?.form_name}</h4>
                </div>
                <div className="nav-btn-wrapper">
                    <button className="outline-btn" onClick={() => setOpenPreview(prev => !prev)}>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 16V21M12 16L18 21M12 16L6 21M21 3V11.2C21 12.8802 21 13.7202 20.673 14.362C20.3854 14.9265 19.9265 15.3854 19.362 15.673C18.7202 16 17.8802 16 16.2 16H7.8C6.11984 16 5.27976 16 4.63803 15.673C4.07354 15.3854 3.6146 14.9265 3.32698 14.362C3 13.7202 3 12.8802 3 11.2V3M8 9V12M12 7V12M16 11V12M22 3H2" stroke="black" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                    </svg>
                    Preview
                    </button>
                    <button onClick={()=>handlePostRequest(`${serverAddress}/head_office/bespokeforms/save_form_json/${id}`)} className="primary-btn" disabled={loadingSave}>
                        {loadingSave ? <CircularProgress sx={{color:'white'}} size='18px' /> : 
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 3H3M18 13L12 7M12 7L6 13M12 7V21" stroke="black" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                    </svg>
                    }
                    Publish
                    </button>
                    <SplitButton/>
                </div>
            </nav>
            <DndContext sensors={sensors}>
                <div className="main-wrap-design">
                    <DesignerSidebar/>
                        <Designer/>
                    <PropertiseSidebar/>
                </div>
                <DragOverlayWrapper/>
            </DndContext>

            <PreviewDesigner/>

        </div>
    );
};

export default App;
