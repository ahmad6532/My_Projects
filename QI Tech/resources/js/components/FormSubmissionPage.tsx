import React, { useCallback, useEffect, useRef, useState } from "react";
import useDesigner from "./hooks/useDesigner";
import SplitButton from "./SplitButton";
import { Box, Button, Container, Stack, Step, StepLabel, Stepper, Typography } from "@mui/material";
import { FormElementInstance, FormElements, FormPage } from "./FormElements";
import { toast } from "react-toastify";

const FormSubmissionPage = ({content}:{content:FormPage[]}) => {
    const formValues = useRef<{[key:string]: string }>({})
    const formErrors = useRef<{[key:string]: boolean }>({});
    const [renderKey,setRenderKey] = useState(new Date().getTime());
    const { openPreview, setOpenPreview, elements } = useDesigner();
    const [activeStep, setActiveStep] = React.useState(0);

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


    const validateForm:()=>boolean = useCallback(()=>{
        const allQuestions = content.flatMap((page)=>page.questions);
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
    },[content])
    // useEffect(()=>{
    //     content = elements;;
    // },[elements])
    const submitValues = useCallback((key:string, value:string)=>{formValues.current[key] = value},[]);

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
                                    return <FormComponent key={question.id} elementInstance={question} submitValue={submitValues} isInvalid={formErrors.current[question.id]} defaultValue={formValues.current[question.id]}/>
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
    );
};

export default FormSubmissionPage;
