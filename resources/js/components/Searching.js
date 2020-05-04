import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

import { COLOUR_SUCCESS, COLOUR_DANGER } from '../constants/colours'; 

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
      		stepFive: false,
      		progressWidth: '25%'
		}
	}

	componentDidMount() {
		setTimeout((self) => {
			self.setState({
				stepOne: true,
				progressWidth: '50%'
			});
		}, 2000, this);

		setTimeout((self) => {
			self.setState({
				stepTwo: true,
				progressWidth: '75%'
			});
		}, 4000, this);

		setTimeout((self) => {
			self.setState({
				stepThree: true,
				progressWidth: '90%'
			});
		}, 6000, this);

		setTimeout((self) => {
			self.setState({
				stepFour: true,
				progressWidth: '100%'
			});
			if (this.props.visitor.uid) {
				this.props.fetchOffer(this.props.visitor.uid);
			}
		}, 8000, this);
	}

	componentDidUpdate(prevProps) {
		if ( (this.props.offer.success !== prevProps.offer.success) && (this.props.offer.success == true) ) {
			this.setState({
				searching: false
			});
		} else if ( (this.props.offer.success !== prevProps.offer.success) && (this.props.offer.success == false) ) {
			setTimeout((self) => {
				self.setState({
					stepFive: true,
					searching: false
				});
			}, 2000, this);
		}
	}

	handleOfferClick() {
		document.location.href = this.props.offer.url;
	}

	render() {
		const platforms = {
			'ios': 'iTunes',
			'android': 'Google Play'
		};

		const platform = platforms[this.props.visitor.device];

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
				return (<span><span role='img' aria-label='fingers-crossed' style={{ fontSize: '1.2rem', verticalAlign: 'middle' }}>🤞</span>Searching database for coupons...</span>);
			} else {
				return (<span><FontAwesomeIcon icon='check' color={ COLOUR_SUCCESS } />{' '}Found available coupon</span>);
			}
		}

		const StepTwo = () => {
			if (!this.state.stepTwo) {
				return (<span><span role='img' aria-label='fingers-crossed' style={{ fontSize: '1.2rem', verticalAlign: 'middle' }}>🤞</span>Verifying coupon on { platform }</span>);
			} else {
				return (<span><FontAwesomeIcon icon='check' color={ COLOUR_SUCCESS } />{' '}Coupon verified with { platform }</span>);
			}
		}

		const StepThree = () => {
			if (!this.state.stepThree) {
				return (<span><span role='img' aria-label='fingers-crossed' style={{ fontSize: '1.2rem', verticalAlign: 'middle' }}>🤞</span>Verifying coupon with { this.props.visitor.carrier }</span>);
			} else {
				return (<span><FontAwesomeIcon icon='check' color={ COLOUR_SUCCESS } />{' '}Coupon available for { this.props.visitor.carrier }</span>);
			}
		}

		const StepFour = () => {
			if (!this.state.stepFour) {
				return (<span><span role='img' aria-label='fingers-crossed' style={{ fontSize: '1.2rem', verticalAlign: 'middle' }}>🤞</span>Checking if coupon hasn't been claimed</span>);
			} else if ( (!this.props.offer.loading) && (!this.props.offer.success) ) {
				return (<span><FontAwesomeIcon icon='times' color={ COLOUR_DANGER } />{' '}Coupon expired <span role='img' aria-label='disappointed' style={{ fontSize: '1rem', verticalAlign: 'middle' }}>😞</span></span>);
			} else {
				return (<span><FontAwesomeIcon icon='check' color={ COLOUR_SUCCESS } />{' '}Coupon Valid!</span>);
			}
		}

		const StepFive = () => {
			if (!this.state.stepFive) {
				return (<span><span role='img' aria-label='fingers-crossed' style={{ fontSize: '1.2rem', verticalAlign: 'middle' }}>🤞</span>Searching for other coupons</span>);
			} else {
				return (<span><FontAwesomeIcon icon='check' color={ COLOUR_SUCCESS } />{' '}Coupon Valid!</span>);
			}
		}

		const CardBody = () => {
			if (this.state.searching) {
				return (
					<ul className='searching-progress-list' style={{ textAlign: 'left', listStyleType: 'none' }}>
						<li><StepOne /></li>
						{ (this.state.stepOne) ? <li><StepTwo /></li> : null }
						{ ( (this.state.stepOne) && (this.state.stepTwo) ) ? <li><StepThree /></li> : null }
						{ ( (this.state.stepOne) && (this.state.stepTwo) && (this.state.stepThree) ) ? <li><StepFour /></li> : null }
						{ ( (this.state.stepOne) && (this.state.stepTwo) && (this.state.stepThree) && (this.state.stepFour) ) ? <li><StepFive /></li> : null }
					</ul>
				);
			} else if ( (!this.state.searching) && (this.props.offer.success) ) {
				return (
					<div>
						<FontAwesomeIcon icon='check' color={ COLOUR_SUCCESS } style={{ marginBottom: '1rem', fontSize: '2rem' }} />
						<h4 className="alert alert-success">Valid Coupon Found!</h4>
						<p>Tap the button below to redeem coupon</p>
					</div>
				);
			} else if (this.props.offer.success == false) {
				return (
					<div>
						<p className="alert alert-warning">Unfortunately coupon expired</p>
						<p>However, you may be interested in the coupons below</p>
					</div>
				);
			} else {
				return null;
			}
		}

		return (
			<div className="card" style={{ marginTop: '1rem' }}>
				<div className="card-body">
					<CardBody />
					<Progress />
				</div>
				<div className="card-footer">
					{ 
						( (this.props.offer.success) && (!this.state.searching) )
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
