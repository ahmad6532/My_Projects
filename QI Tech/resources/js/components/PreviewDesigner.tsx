import * as React from "react";
import Button from "@mui/material/Button";
import Dialog from "@mui/material/Dialog";
import AppBar from "@mui/material/AppBar";
import Toolbar from "@mui/material/Toolbar";
import IconButton from "@mui/material/IconButton";
import Typography from "@mui/material/Typography";
import CloseIcon from "@mui/icons-material/Close";
import Slide from "@mui/material/Slide";
import { TransitionProps } from "@mui/material/transitions";
import useDesigner from "./hooks/useDesigner";
import { FormElements } from "./FormElements";
import { Box, Container, Stack } from "@mui/material";
import Stepper from '@mui/material/Stepper';
import Step from '@mui/material/Step';
import StepLabel from '@mui/material/StepLabel';
import SplitButton from "./SplitButton";
import { toast } from "react-toastify";

const Transition = React.forwardRef(function Transition(
    props: TransitionProps & {
        children: React.ReactElement;
    },
    ref: React.Ref<unknown>
) {
    return <Slide direction="up" ref={ref} {...props} />;
});

export default function PreviewDesigner() {
    const formValues = React.useRef<{[key:string]: string }>({})
    const formErrors = React.useRef<{[key:string]: boolean }>({});
    const [renderKey,setRenderKey] = React.useState(new Date().getTime());
    const [activeStep, setActiveStep] = React.useState(0);
    const { openPreview, setOpenPreview, elements } = useDesigner();



    const handleClickOpen = () => {
        setOpenPreview(true);
    };

    const handleClose = () => {
        setOpenPreview(false);
    };

    const handleNext = () => {
        setActiveStep((prevActiveStep) => prevActiveStep + 1);
    };

    const handleBack = () => {
        setActiveStep((prevActiveStep) => prevActiveStep - 1);
    };

    const handleReset = () => {
        setActiveStep(0);
    };
    const validateForm:()=>boolean = React.useCallback(()=>{
        const allQuestions = elements.flatMap((page)=>page.questions);
        for(const field of allQuestions){
            const actualValue = formValues.current[field.id] || '';
            const valid = FormElements[field.type].validate(field,actualValue);
            if(!valid){
                formErrors.current[field.id] = true;
            }
        }
        if(Object.keys(formErrors.current).length>0){
            return false;
        }
        return true;
    },[elements])
    // useEffect(()=>{
    //     content = elements;;
    // },[elements])
    const submitValues = React.useCallback((key:string, value:string)=>{formValues.current[key] = value},[]);

    const submitForm = ()=>{
        formErrors.current = {};
        const validForm = validateForm();
        if(!validForm){
            setRenderKey(new Date().getTime());
            toast.error("Error Notification !", {
                position: "top-right"
              });
            return;
        }

    }

    return (
        <React.Fragment>
            <Dialog
                fullScreen
                open={openPreview}
                onClose={handleClose}
                TransitionComponent={Transition}
                PaperProps={{
                    style: {
                        backgroundColor: '#F3F3F3',
                        boxShadow: 'none',
                    },
                }}
            >
                <AppBar sx={{ position: "relative" }}>
                    <Toolbar>
                        <IconButton
                            edge="start"
                            color="inherit"
                            onClick={handleClose}
                            aria-label="close"
                        >
                            <CloseIcon sx={{color:'white'}} />
                        </IconButton>
                        <Typography
                            sx={{ ml: 2, flex: 1 }}
                            variant="h6"
                            component="div"
                            color='white'
                        >
                            Form Preview
                        </Typography>
                        {/* <Button autoFocus color="inherit" onClick={handleClose}>
                            Save
                        </Button> */}
                    </Toolbar>
                </AppBar>
                <div style={{background:'#F3F3F3'}} key={renderKey}>
            <header className="p-3 px-5" style={{background:'white',boxShadow:'0px 2px 0px #19b394'}}>
                <div>
                    <Typography variant="h3" fontWeight='bold' fontFamily='Littera Text' color='#19b394'>Form Builder</Typography>
                    <Typography variant="h6" color='#909090' fontFamily='Littera Text'>Form Submission Page</Typography>
                </div>
            </header>
            <Container maxWidth="lg" sx={{minHeight: '100vh',marginTop:4}}>
            <Stack sx={{ backgroundColor: "#F3F3F3 !important", p: 5, gap: 2,width:"60%",marginInline:"auto",mt:3 }}>
                    <Stepper activeStep={activeStep} sx={{mb:3}}>
                        {elements.map((page, index) => (
                            <Step key={page.id}>
                            <StepLabel
                StepIconProps={{
                    sx: {
                        fontSize: '32px',
                        '& .MuiStepIcon-text': {
                            fill: "white",
                            fontSize: '16px', 
                        },
                    }
                }}
                sx={{
                    '& .MuiStepLabel-label': {
                        fontSize: '20px',
                        color: index === activeStep ? '#19b394' : '#909090',
                    }
                }}
            >
                {page.name}
            </StepLabel>
                        </Step>
                        ))}
                    </Stepper>
                    {activeStep === elements.length ? (
                        <Stack sx={{minHeight:"350px"}} justifyContent='space-between'>
                            <Typography sx={{ mt: 2, mb: 1 }}>
                                All steps completed - you&apos;re finished
                            </Typography>
                            <Box sx={{ display: 'flex', flexDirection: 'row', pt: 2 }}>
                                <Box sx={{ flex: '1 1 auto' }} />
                                <Button onClick={handleReset}>Reset</Button>
                            </Box>
                        </Stack>
                    ) : (
                        <>
                            <Stack sx={{minHeight:"350px"}}>
                                <Typography variant="h4" fontWeight="bold">{elements[activeStep].name}</Typography>
                                {elements[activeStep].description && (
                                    <Typography variant="h6" fontWeight="normal" color="#909090">{elements[activeStep].description}</Typography>
                                )}
                                {elements[activeStep].questions.map(question => {
                                    const FormComponent = FormElements[question.type].formComponent;
                                    return (<div style={{marginTop:5}}><FormComponent key={question.id} elementInstance={question} submitValue={submitValues} isInvalid={formErrors.current[question.id]} defaultValue={formValues.current[question.id]}/></div>)
                                })}
                            </Stack>
                            <Box sx={{ display: 'flex', flexDirection: 'row', pt: 2 }}>
                                <Button
                                    color="inherit"
                                    disabled={activeStep === 0}
                                    onClick={handleBack}
                                    sx={{ mr: 3,fontSize:'16px' }}
                                    variant="outlined"
                                >
                                    Back
                                </Button>
                                <Button variant='contained' sx={{ fontSize:'16px' }} onClick={activeStep === elements.length - 1 ? submitForm : handleNext}>
                                    {activeStep === elements.length - 1 ? 'Finish' : 'Next'}
                                </Button>
                                <Box sx={{ flex: '1 1 auto' }} />
                            </Box>
                        </>
                    )}
                </Stack>
            </Container>
        </div>
            </Dialog>
        </React.Fragment>
    );
}


