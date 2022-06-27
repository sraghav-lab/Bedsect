base <- "path to bedsect installation"
if (!require(UpSetR)) install.packages('UpSetR')
if (!require(corrplot)) install.packages('corrplot')
if (!require(dplyr)) install.packages('dplyr')
if (!require(SuperExactTest)) install.packages('SuperExactTest')
if (!require(shiny)) install.packages('shiny')
if (!require(shinythemes)) install.packages('shinythemes')
if (!require(shinyjs)) install.packages('shinyjs')

library(UpSetR)
library(corrplot)
library(dplyr)
library(SuperExactTest)
library(shinythemes)
library(shiny)
library(shinyjs)

load_data <- function() {
  Sys.sleep(3)
  hide("loading-spinner")
  show("main_content")
}

ui = tagList(
	#tags$title("Bedsect"),
    	navbarPage("Plots",
      	theme = shinytheme("paper"),# <--- To use a theme, uncomment this
     	#theme = "bootstrap.min.css", 
	#tags$img(src="logo.png"),
	#navbarMenu("Plots"),
     	tabPanel("UpSetR",
			sidebarPanel(width = 2,
	  		sliderInput("width","plot width:",900,1500,10),
	  		sliderInput("height","plot heigth:",600,800,10),
          		sliderInput("mbratio", "MB Ratio:", 0.25, 0.7, 0.05),
          		selectInput("order", "Order by:",c("Degree" = "degree","Frequency" = "freq")),
          		sliderInput("setSize", "Set size angle:", -90, 90, 10),
          		sliderInput("numAng", "Bar number angle:", -90, 90, 10),
	  		radioButtons(inputId = "var3", label = "Select the file type", choices = list("png", "pdf")),
	  		downloadButton(outputId = "down1", label = "Download", class="btn-success")
        ),
        mainPanel(
              	h2("UpSet Plot"),
		useShinyjs(),
		div(
			tags$img(src = "spinner.gif",
               		id = "loading-spinner")
		),
		fluidRow(
                     column(2,align="center",uiOutput("upset.ui")))
		#textOutput("txtout")
		#tags$style(type='text/css', "#queryText{background-color: white; color: white;margin-top: -800px;}")
        )
      ),
      tabPanel("Corr Plot",
        	sidebarPanel(width = 2,
	  		sliderInput("textsize", "Label Size:", 0.1, 5, 1),
	  		sliderInput("colorsize","Size of color label:",0.1,5,1),
	  		selectInput("shape","Shape:",c("circle"="circle","square"="square","ellipse"="ellipse","number"="number","pie"="pie", "shade"="shade","color"="color")),
          		selectInput("hc_method", "Hierarchical clustering methods:",c("Ward"="ward", "Ward.D"="ward.D", "Ward.D2"="ward.D2", "Single"="single", "Complete"="complete", "Average"="average", "Mcquitty"="mcquitty", "Median"="median","Centroid"="centroid")),
          		sliderInput("Addrect", "addrect:", 0, 20, 1),
	  		radioButtons(inputId = "var4", label = "Select the file type", choices = list("png", "pdf")),
          		downloadButton(outputId = "down2", label = "Download", class="btn-success")
        ),
        mainPanel(
              	h2("Correlation Plot"),
              	fluidRow(
                     column(8,align="center",plotOutput("corr",width = "50%")))
        )
      )
    )
  )

######################################################################################################
server = function(input, output, session) {
	load_data() 
  	path <- function(){
		query <- parseQueryString(session$clientData$url_search)
    		paste(base,names(query),"/corr_matrix",sep = "")
  	}
    	
	output$txtout <- renderText({
		path()
    	})

########################################Upset plot###################################################
	#Render the main plot once to reduce the render time fro plots
	upsetPlot <- function(){
		#Read the file form query path
		bars<<-read.csv(path(),sep="\t",header=T,check.names=FALSE)
		mat_prop <- reactive({
    			mat_prop <- input$mbratio
  		})
  		bar_prop <- reactive({
   			bar_prop <- (1 - input$mbratio)
  		})
        	name <- colnames(bars[,2:length(bars)])
        	upset(bars,sets=name,main.bar.color = "black", matrix.color="black",sets.bar.color="black",set_size.angles=input$setSize,number.angles = input$numAng,text.scale=2,order.by =input$order,point.size = 2,mb.ratio=c(as.double(bar_prop()), as.double(mat_prop())))
	}

#######################################################################################################
# Render height and width
	output$upset.ui <- renderUI({
            plotOutput("upset", width = input$width, height = input$height)
        })

#######################################################################################################
# Render upset plot
    	output$upset <- renderPlot({
		upsetPlot()})#,height = input$height, width = input$width)
		
######################################correlation plot ################################################
	corr_plot <- function(){
		N <- length(bars) - 1
        	name <- colnames(bars[,2:length(bars)])
        	obj <- vector("list",N)
        	for(i in 1:N) {
                	ID <- as.vector(bars %>% filter(bars[,i+1] == 1) %>% select(list) %>% unlist())
                	obj[[i]] <- ID
        	}
		#obj <- obj[rowSums(obj)>1,]
        	names(obj) <- name
        	cda <- jaccard(obj)
        	col2 <- colorRampPalette(c("#67001F", "#B2182B", "#D6604D", "#F4A582","#FDDBC7", "#FFFFFF", "#D1E5F0", "#92C5DE","#4393C3", "#2166AC", "#053061"))
		corrplot(cda,method = input$shape,col = col2(400),order="hclust",hclust.method = input$hc_method,title="",cl.lim = c(0, 1),addrect =input$Addrect, tl.col = "black",tl.cex=input$textsize,cl.cex = input$colorsize)
	}
########################################################################################################
# Render correlation plot
    	output$corr <- renderPlot({
		corr_plot()}, height = 700, width = 700)
########################################################################################################
# Downlaod  upset plot downloadHandler contains 2 arguments as functions, namely filename, content
	output$down1 <- downloadHandler(
	    filename =  function() {
	    	paste("upsetR", input$var3, sep=".")
	    },
	    # content is a function with argument file. content writes the plot to the device
	    content = function(file) {
	      if(input$var3 == "png")
		      	#Solved issue with UpsetR plots https://stackoverflow.com/a/14812779
		        png(file,width =6000,height=2500,unit="px",res=400) # open the png device
	    			    	
	      else
			pdf(file, width=20, height=15,onefile=FALSE)#,width =1000,height=1000,unit="px") # open the pdf device
			print(upsetPlot())
	    		dev.off()  # turn the device off
    		}		 
	)
#########################################################################################################
#Downlaod correlation plot
	output$down2 <- downloadHandler(
            filename =  function() {
              paste("Corr", input$var4, sep=".")
            },
            # content is a function with argument file. content writes the plot to the device
            content = function(file) {
              if(input$var4 == "png")
                        png(file,width =6000,height=2000,unit="px",res=400) # open the png device
                else
                        pdf(file,width =20,height=20,onefile=FALSE) # open the pdf device
                        print(corr_plot()) 
                        dev.off()  # turn the device off
                }
        )
########################################################################################################
}
shinyApp(ui, server)
