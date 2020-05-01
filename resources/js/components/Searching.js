import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

import { fetchOffer } from '../actions/offer';

class Searching extends Component {

	constructor(props) {
		super(props);

		this.state = {
			searching: true,
			stepOne: false,
      		stepTwo: false,
      		stepThree: false,
      		stepFour: false,
      		progressWidth: '25%'
		}
	}

	componentDidMount() {
		var self = this;

		//This will actually run after the fake set timeouts
		if (this.props.visitor.uid) {
			this.props.fetchOffer(this.props.visitor.uid);
		}

		setTimeout((self) => {
			self.setState({
				stepOne: true,
				progressWidth: '50%'
			});
		}, 2000, this);
	}

	componentDidUpdate(prevProps) {
		if (this.props.visitor.uid !== prevProps.visitor.uid) {
			this.props.fetchOffer(this.props.visitor.uid);
		}

		// if ( (this.state.stepOne !== prevProps.stepOne) && (this.state.stepOne) ) {
		// 	console.log('stepOneUpdated');
	 //        setTimeout((self) => {
	 //          self.setState({
	 //            stepTwo: true,
	 //            progressWidth: '75%'
	 //          });
	 //        }, 2000, this);
  //     	}

  //     	if ( (this.state.stepTwo !== prevProps.stepTwo) && (this.state.stepTwo) ) {
  //     		console.log('stepTwoUpdated');
	 //        setTimeout((self) => {
	 //          self.setState({
	 //            stepThree: true,
	 //            progressWidth: '80%'
	 //          });
	 //        }, 2000, this);
  //     	}

  //     	if ( (this.state.stepThree !== prevProps.stepThree) && (this.state.stepThree) ) {
  //     		console.log('stepThreeUpdated');
	 //        setTimeout((self) => {
	 //          self.setState({
	 //            stepFour: true,
	 //            progressWidth: '90%'
	 //          });
	 //        }, 2000, this);
      	}
	}

	handleOfferClick() {
		document.location.href = this.props.offer.url;
	}

	render() {
		const Progress = () => {
			if (this.state.searching) {
				return (
					<div className="progress">
						<div className="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style={{ width: this.state.progressWidth}}></div>
					</div>
				);
			} else {
				return (<div></div>);
			}
		};

		const StepOne = () => {
			if (!this.state.stepOne) {
				return (<span>Searching Step One...</span>);
			} else {
				return (<span><FontAwesomeIcon icon='check' />{' '}Done Step One</span>);
			}
		}

		const StepTwo = () => {
			if (!this.state.stepTwo) {
				return (<span>Searching Step Two...</span>);
			} else {
				return (<span><FontAwesomeIcon icon='check' />{' '}Done Step Two</span>);
			}
		}

		const StepThree = () => {
			if (!this.state.stepThree) {
				return (<span>Searching Step Three...</span>);
			} else {
				return (<span><FontAwesomeIcon icon='check' />{' '}Done Step Three</span>);
			}
		}

		const StepFour = () => {
			if (!this.state.stepFour) {
				return (<span>Searching Step Four...</span>);
			} else {
				return (<span><FontAwesomeIcon icon='check' />{' '}Done Step Four</span>);
			}
		}

		return (
			<div className="card" style={{ marginTop: '1rem' }}>
				<div className="card-body"> 
					{/* { (!this.state.searching) ? <div><span className="badge badge-success"><FontAwesomeIcon icon='check' /></span>{' '}Coupon Found</div> : <div>Searching</div> } */}
					<ul style={{ textAlign: 'left', listStyleType: 'none' }}>
						<li><StepOne /></li>
						{ (this.state.stepOne) ? <li><StepTwo /></li> : null }
						{ ( (this.state.stepOne) && (this.state.stepTwo) ) ? <li><StepThree /></li> : null }
						{ ( (this.state.stepOne) && (this.state.stepTwo) && (this.state.stepThree) ) ? <li><StepFour /></li> : null }
					</ul>
					<Progress />
					{ ( (this.props.offer.url) && (!this.state.searching) ) ? <a href={ this.props.offer.url } class="btn btn-success">Redeem Coupon</a> : <div></div> }
				</div>
				<div className="card-footer">
					{ 
						( (this.props.offer.url) && (!this.state.searching) )
						?
						<button onClick={ this.handleOfferClick.bind(this) } className='input-group' style={{ width: '100%', padding: 0, 'border': 0, background: 'transparent' }}>
							<div className='btn btn-outline-success border-right-flat' style={{ width: '80%', background: 'white' }}>Redeem Coupon</div>
							<div className='input-group-append' style={{ width: '20%' }}>
								<div className='input-group-text' style={{ width: '100%', paddingLeft: '40%', borderRadius: '0 2.25rem 2.25rem 0', background: '#38C192', border: '1px solid #38C192', color: 'white' }}>
									<FontAwesomeIcon icon='angle-right' />
								</div>
							</div>
						</button>
						:
						<div></div>
					}
				</div>
			</div>
		);
	}

}

const mapStateToProps = state => ({
	visitor: state.visitor,
	offer: state.offer
});

export default connect(mapStateToProps, { fetchOffer })(Searching);
