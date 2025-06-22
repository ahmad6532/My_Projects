import {
    DraggableAttributes,
    DraggableSyntheticListeners,
} from "@dnd-kit/core";
import {
    ElementsType,
    FormElement,
    FormElementInstance,
    SubmitFunction,
} from "../FormElements";
import useDesigner from "../hooks/useDesigner";
import z, { string } from "zod";
import {useForm,Controller} from 'react-hook-form'
import {zodResolver}  from '@hookform/resolvers/zod'
import { useEffect, useRef, useState } from "react";
import {Alert, Checkbox, Divider, FormControlLabel, IconButton, Slider, Stack, TextField, Tooltip, Typography} from '@mui/material';
const type: ElementsType = "SpacerField";

const extraAttributes = {
    height: 10,
};

const propertiseSchema = z.object({
    height: z.number().min(5).max(50),
    id:z.string().min(1).max(200)
})


export const SpacerFieldFormElement: FormElement = {
    type,

    construct: (id: string) => ({
        id,
        type,
        extraAttributes,
    }),
    designerBtnElement: {
        icon: (
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M21 10H13M21 6H13M21 14H13M21 18H13M6 20L6 4M6 20L3 17M6 20L9 17M6 4L3 7M6 4L9 7" stroke="black" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
</svg>



        ),
        label: "Spacing",
    },
    designerComponent: DesignerComponent,
    formComponent: FormComponent,
    propertiseComponent: PropertiseComponent,
    validate: ()=>true,
};

type CustomInstance = FormElementInstance & {
    extraAttributes: typeof extraAttributes;
};

function DesignerComponent({
    pageId,
    elementInstance,
    listeners,
    attributes,
}: {
    pageId:string;
    elementInstance: FormElementInstance;
    listeners?: DraggableSyntheticListeners;
    attributes?: DraggableAttributes;
}) {
    const { removeElement, setSelectedItem,updateElement,setSelectedPage,setSelectedPageId } = useDesigner();
    const element = elementInstance as CustomInstance;
    const { height } = element.extraAttributes;

    function updateRequired() {
        updateElement(pageId,element.id, {
            ...element,
            extraAttributes: {
                ...element.extraAttributes,
                required: !element.extraAttributes.required,
            },
        });
    }
    return (
        <div
            className="main-question-wrapper"
            key={element.id}
            onClick={(e) => {
                e.stopPropagation();
                setSelectedPageId(pageId);
                setSelectedPage(null);
                setSelectedItem(element);
            }}
            style={{paddingBlock:'22px'}}
        >
            <div className="drag-area" {...listeners} {...attributes}>
                <svg viewBox="0 0 20 20">
                    <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                </svg>
            </div>
            <div className="input-area">
                <Stack  spacing={0} alignItems="center" justifyContent='center'>
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 10H13M21 6H13M21 14H13M21 18H13M6 20L6 4M6 20L3 17M6 20L9 17M6 4L3 7M6 4L9 7" stroke="#999" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                </svg>
                <Typography variant="h6">{height}px</Typography>
                </Stack>
            </div>

            <div className="question-actions">
                <div></div>
                <div className="right-actions">
                    <button className="light-btn" onClick={(e) => {e.stopPropagation()}}>
                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M3 8L15 8M15 8C15 9.65686 16.3431 11 18 11C19.6569 11 21 9.65685 21 8C21 6.34315 19.6569 5 18 5C16.3431 5 15 6.34315 15 8ZM9 16L21 16M9 16C9 17.6569 7.65685 19 6 19C4.34315 19 3 17.6569 3 16C3 14.3431 4.34315 13 6 13C7.65685 13 9 14.3431 9 16Z"
                                stroke="black"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>
                        Settings
                    </button>
                    <button
                        className="light-btn"
                        onClick={(e) => {
                            e.stopPropagation;
                            removeElement(pageId,element.id);
                        }}
                    >
                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5"
                                stroke="black"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    );
}
function FormComponent({
    elementInstance,
    submitValue,
    isInvalid,
    defaultValue
}: {
    elementInstance: FormElementInstance;
    submitValue?: SubmitFunction;
    isInvalid?: boolean;
    defaultValue?:string
}) {
    const { removeElement, setSelectedItem } = useDesigner();
    const [value,setValue] =useState(defaultValue || "");
    const [error,setError] =useState(false);
    useEffect(()=>{
        setError(isInvalid === true);

    },[isInvalid])
    const element = elementInstance as CustomInstance;
    const { height } = element.extraAttributes;
    return (
        <div
            className="main-question-wrapper prev"
            style={{padding:'0px',background:'transparent',boxShadow:'none',margin:'0px'}}
        >
            <div className="input-area" style={{padding:'0px',background:'transparent',boxShadow:'none',margin:'0px'}}>
                <div style={{height:height+'px',background:'transparent'}}>
                    
                </div>
            </div>
            {
                error && 
                <Alert sx={{width:'94%',position:'absolute',bottom:"5px"}} severity="error">Response is required</Alert>
            }
        </div>
    );
}

type propertiseFormSchemaType = z.infer<typeof propertiseSchema>
function PropertiseComponent({elementInstance}:{
    elementInstance: FormElementInstance}){
        const element = elementInstance as CustomInstance;
        const {watch,control, handleSubmit, formState: { errors }, getValues,reset,setError} = useForm<propertiseFormSchemaType>({
            resolver: zodResolver(propertiseSchema),
            mode:'onChange',
            defaultValues:{
                height: element.extraAttributes.height,
                id:element.id
            }
        });


        const {updateElement,elements,setSelectedItem,selectedPageId} = useDesigner();
        const pageIndex = elements.findIndex(page => page.id == selectedPageId)
        const found = elements[pageIndex].questions.find(e => e.id === element.id)
        useEffect(() => {
            if (found) {
                reset({id:found.id,height:found.extraAttributes?.height});
            }
        }, [element, reset]);
        // useEffect(() => {
        //     reset(element.extraAttributes);
        // }, [element, reset]);

        function applyChanges(values:propertiseFormSchemaType){
            const {height} = values;
            updateElement(selectedPageId,element.id,{
                ...element,extraAttributes:{
                    height
                }
            })
        }
        const isIdUnique = (id: string) => {
            const allQuestions = elements.flatMap(page => page.questions);
            return !allQuestions.some(el => el.id === id);
        };
        function idchange(value:string){
            if(isIdUnique(value)){
                updateElement(selectedPageId,element.id,{
                    ...element,id:value
                })
                setSelectedItem(element);
                return;
            }else{
                if(value !== element.id){
                    setError('id', {
                        type: 'manual',
                        message: 'ID must be unique'
                    });
                }
            }
        }
        return (
            <>
            <Typography variant="body1" color="GrayText">General</Typography>
                <Divider orientation="horizontal" sx={{ marginBottom: 3, borderColor: 'rgba(0,0,0,0.5)' }} />
                <Stack direction={'row'} spacing={1} alignItems={"center"} mb={2}>
                <Controller
                        name="id"
                        shouldUnregister
                        control={control}
                        render={({ field }) => (
                            <TextField
                                {...field}
                                onBlur={e=>idchange(e.target.value)}
                                error={!!errors.id}
                                helperText={errors.id ? errors.id.message : ''}
                                label="Question ID"
                                fullWidth
                                variant="outlined"
                                size="small"
                                multiline
                            />
                        )}
                    />
                    <Tooltip title="A Question ID that is not visible to users">
                    <IconButton>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.09 9C9.3251 8.33167 9.78915 7.76811 10.4 7.40913C11.0108 7.05016 11.7289 6.91894 12.4272 7.03871C13.1255 7.15849 13.7588 7.52152 14.2151 8.06353C14.6713 8.60553 14.9211 9.29152 14.92 10C14.92 12 11.92 13 11.92 13M12 17H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="#72C4BA" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                    </svg>

                    </IconButton>
                    </Tooltip>
                </Stack>
            <form onChange={handleSubmit(applyChanges)} className="mt-4 mx-auto w-100 px-2">
                <Typography variant="body1" fontFamily='Littera Text'>Height(px) - {watch('height')}</Typography>
                <Controller
                    name="height"
                    control={control}
                    render={({ field }) => (
                        <Slider 
                        valueLabelDisplay="auto" min={5} max={200} aria-label="Volume" {...field} onChange={(e,value) => {
                            field.onChange(value);
                            applyChanges({ ...getValues(), height: value as number });
                        }}  />
                    )}
                />

                
            </form>
        </>
        )
}


