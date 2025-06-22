import { Stack, Typography, Divider, TextField, Tooltip, IconButton } from "@mui/material";
import React, { useEffect } from "react";
import { Controller, useForm } from "react-hook-form";
import z from 'zod';
import useDesigner from "./hooks/useDesigner";
import { zodResolver } from "@hookform/resolvers/zod";
const propertiseSchema = z.object({
    pageName: z.string().max(50),
    pageDescription: z.string().max(1000),
    id:z.string().min(1).max(200)
})
type propertiseFormSchemaType = z.infer<typeof propertiseSchema>
const PagesPropertise = () => {
    const {selectedPage,elements,setElements,selectedPageId} = useDesigner();

    const {control, handleSubmit, formState: { errors }, getValues,reset,setError} = useForm<propertiseFormSchemaType>({
        resolver: zodResolver(propertiseSchema),
        mode:'onChange',
        defaultValues:{
            pageName: selectedPage?.name,
            id:selectedPage?.id,
            pageDescription:selectedPage?.description
        }
    });
    const page = elements.find(page => page.id === selectedPageId)

    const isIdUnique = (id: string) => {
        return !elements.some(el => el.id === id);
    };
    function idchange(value:string){
        if(isIdUnique(value)){
            setElements((prev) => {
                return prev.map((page) => {
                    if (page.id === selectedPageId) {
                        return {
                            ...page,
                            id: value
                        };
                    }
                    return page;
                });
            })
            return;
        }else{
            setError('id', {
                type: 'manual',
                message: 'ID must be unique'
            });
        }
    }

    function applyChanges(values:propertiseFormSchemaType){
        const {pageName,pageDescription} = values;
        setElements((prev) => {
            return prev.map((page) => {
                if (page.id === selectedPageId) {
                    return {
                        ...page,
                        name: pageName,
                        description: pageDescription
                    };
                }
                return page;
            });
        })
    }

    useEffect(() => {
        reset({pageName:page?.name,id:page?.id,pageDescription:page?.description});
    }, [page,reset]);
    
    return (
        <Stack>
            <Typography variant="body1" color="GrayText">
                General
            </Typography>
            <Divider
                orientation="horizontal"
                sx={{ marginBottom: 3, borderColor: "rgba(0,0,0,0.5)" }}
            />
            <Stack direction={"row"} spacing={1} alignItems={"center"} mb={2}>
                <Controller
                    name="id"
                    shouldUnregister
                    control={control}
                    render={({ field }) => (
                        <TextField
                            {...field}
                            onBlur={(e) => idchange(e.target.value)}
                            error={!!errors.id}
                            helperText={errors.id ? errors.id.message : ""}
                            label="Question ID"
                            fullWidth
                            variant="outlined"
                            size="small"
                            multiline
                        />
                    )}
                />
                <Tooltip title="A Pages ID that is not visible to users">
                    <IconButton>
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M9.09 9C9.3251 8.33167 9.78915 7.76811 10.4 7.40913C11.0108 7.05016 11.7289 6.91894 12.4272 7.03871C13.1255 7.15849 13.7588 7.52152 14.2151 8.06353C14.6713 8.60553 14.9211 9.29152 14.92 10C14.92 12 11.92 13 11.92 13M12 17H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                                stroke="#72C4BA"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>
                    </IconButton>
                </Tooltip>
            </Stack>
            <form
                onChange={handleSubmit(applyChanges)}
                className="mt-4 mx-auto w-100"
            >
                <Controller
                    name="pageName"
                    control={control}
                    render={({ field }) => (
                        <TextField
                            {...field}
                            error={!!errors.pageName}
                            helperText={
                                errors.pageName
                                    ? "Please enter a valid value"
                                    : ""
                            }
                            label="Name"
                            fullWidth
                            variant="outlined"
                            size="small"
                        />
                    )}
                />
                <Controller
                    name="pageDescription"
                    control={control}
                    render={({ field }) => (
                        <TextField
                        sx={{mt:2}}
                            {...field}
                            error={!!errors.pageDescription}
                            helperText={
                                errors.pageDescription
                                    ? "Please enter a valid value"
                                    : ""
                            }
                            label="Description"
                            fullWidth
                            variant="outlined"
                            size="small"
                        />
                    )}
                />
            </form>
        </Stack>
    );
};

export default PagesPropertise;
