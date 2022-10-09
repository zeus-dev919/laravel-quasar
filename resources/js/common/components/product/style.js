import Styled from 'vue3-styled-components';

const ProductCard = Styled.div`
    border-radius: 10px;
    background-color: #fff;
    position: relative;
	width: 100px;

    @media only screen and (max-width: 767px){
        max-width: 350px;
        margin: 0 auto;
    }
    &.list-view{
        max-width: 100%;
        .product-single-price__offer{
            @media only screen and (max-width: 991px) and (min-width: 768px){
                display: block;
            }
        }
    }
    .product-list{
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        figure{
            @media only screen and (max-width: 1199px){
            }
            @media only screen and (max-width: 991px){
                margin: 0 0 20px;
            }
            img{
                border-radius: 10px;
            }
        }
        .product-single-description{
            p{
                font-size: 15px;
            }
        }
        .product-single-title{
            font-size: 18px;
            margin: 25px 0 16px;
            @media only screen and (max-width: 1199px){
                margin: 0 0 16px;
            }
        }
        .product-single-info{
            margin-top: 25px;
            @media only screen and (max-width: 1199px){
                margin-top: 0;
            }
        }
        .product-single-price__new{
            font-size: 16px;
        }
        .product-single-action{
            flex-flow: column;
            align-items: flex-start;
            margin: 28px 0 0 0;
            button{
                min-width: 132px;
                margin: 0;
                padding: 0px 14px;
                height: 38px;
            }
            .btn-cart{
                margin: 0 0 10px;
            }
            .ant-btn-sm{
                height: 38px;
            }
        }
        .btn-heart{
            @media only screen and (max-width: 1599px){
                top: 0;
            }
            @media only screen and (max-width: 1199px){
                top: -4px;
            }
            @media only screen and (max-width: 991){
                top: 0;
            }
        }
    }
    figure{
        margin-bottom: 0;
        img{
            width: 100%;
        }
    }
    figcaption{
        padding: 20px 20px 26px;
    }
    .quantity-box{
        position: absolute;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 15px;
        top: 1px;
        background-color: #fff;
		padding: 0 6px;
		left: 1px;
    }
    .product-single-title{
        margin-bottom: 10px;
        font-size: 15px;
        font-weight: 500;

    }
    .product-single-price{
        margin-bottom: 5px;
        del{
            margin: 0 5px;
        }
    }
    .product-single-price__new{
        font-weight: 600;
    }
    .product-single-price__offer{
        font-weight: 500;
        font-size: 12px;
    }
    .product-single-rating{
        font-size: 12px;
        font-weight: 500;
        display: flex;
        flex-wrap: wrap;
        align-items: center;

        .ant-rate-star{
            div{
                transform: none !important;
            }
        }
        .total-reviews{
            font-weight: 400;
          
        }
        svg{
            width: 13px;
        }
    }
    .product-single-action{
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        margin: 20px -5px -5px -5px;
        button{
            font-size: 12px;
            margin: 5px;
        }


        .ant-btn-sm{
            font-size: 12px;
            padding: 0px 18.065px;
            height: 36px;
        }
    }
`;

const OrderSummary = Styled.div`
    max-width: 650px;
    margin: 0 auto;
    .ant-card{
        margin-bottom: 0 !important;
    }

    .ant-form-item{
        margin-bottom: 0;
    }

    .summary-table-title{
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 25px;
      
    }
    .order-summary-inner{
        padding-bottom: 5px;
        @media only screen and (max-width: 1599px){
            max-width: 600px;
            margin: 0 auto;
        }
        .ant-form-item-control{
            line-height: 2.2;
        }
        .ant-form-item-control-wrapper{
            width: 100%;
        }
        .ant-select{
            .ant-select-selection-item{
                font-weight: 500;
            }
        }
        .ant-select-single:not(.ant-select-customize-input) .ant-select-selector{
            height: 30px !important;
        }
    }
    .invoice-summary-inner{
        .summary-list{
            margin: 22px 0;
            li{
                &:not(:last-child){
                    margin-bottom: 12px;
                }
            }
        }
      
    }

    .summary-list{
		padding: 0;
        li{
            display: flex;
            justify-content: space-between;
            &:not(:last-child){
                margin-bottom: 18px;
            }
            span{
                font-weight: 500;
            }
          
        }
    }
    .ant-select-focused.ant-select-single{
        .ant-select-selector{
            box-shadow: 0 0 !important;
        }
    }
    .ant-select-single{
        margin-top: 18px;
        .ant-select-selection-search-input{
            height: fit-content;
        }
        .ant-select-selector{
            padding: 0 !important;
            border: 0 none !important;
            
        }
       
    }
    .promo-apply-form{
        display: flex;
        align-items: flex-end;
        margin: 5px 0 18px;
        @media only screen and (max-width: 479px){
            flex-flow: column;
            align-items: flex-start;
        }
        .ant-form-item{
            margin-bottom: 0;
        }
        .ant-row{
            flex: auto;
            flex-flow: column;
        }
        .ant-form-item-label{
            text-align: 'right';
            line-height: 30px;
            label{
                font-weight: 400;
                margin-bottom: 4px;
                height: fit-content;
                
            }
        }
        .ant-form-item-control-wrapper{
            display: flex;
            width: 100%;
            @media only screen and (max-width: 479px){
                flex-flow: column;
            }
            .ant-form-item-control{
                width: 100%;
            }
            .ant-form-item-children{
                display: block;
                margin: '0 6px 0 0';
                height: auto;
                @media only screen and (max-width: 479px){
                    margin: '0 6px 10px 0';
                }
            }
            input{
                height: 40px;
                @media only screen and (max-width: 479px){
                    width: 100% !important;
                }
            }
            button{
                height: 40px;
            }
        }
    }
    .summary-total{
        display: inline-flex;
        justify-content: space-between;
        width: 100%;
        .summary-total-label{
            font-size: 16px;
            font-weight: 500;
          
        }
        .summary-total-amount{
            font-size: 18px;
            font-weight: 600;
          
        }
    }
    .btn-proceed{
        font-size: 15px;
        font-weight: 500;
        width: 100%;
        height: 50px;
        border-radius: 8px;
        margin-top: 22px;
        @media only screen and (max-width: 575px){
            font-size: 13px;
        }
        a{
            display: flex;
            align-items: center;
        }
        i,
        svg{
            'margin-left' : 6px;
            color: #fff;
        }
    }
`;

export {
	ProductCard,
	OrderSummary
};
